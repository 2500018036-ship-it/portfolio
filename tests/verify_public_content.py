"""Read-only integration audit of rendered pages, assets, links, and archived content."""
import argparse
import json
import re
from collections import deque
from html.parser import HTMLParser
from urllib.error import HTTPError
from urllib.parse import urldefrag, urljoin, urlsplit
from urllib.request import Request, urlopen


class Page(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.links, self.assets, self.ids, self.text = [], [], set(), []
        self.hidden = 0

    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        if tag in ('script', 'style'):
            self.hidden += 1
        if a.get('id'):
            self.ids.add(a['id'])
        if tag == 'a':
            self.links.append(a.get('href', ''))
        if tag == 'img':
            self.assets.append(a.get('src', ''))
            assert a.get('alt'), 'Image missing alternative text'
        if tag == 'link' and a.get('rel') in ('icon', 'stylesheet'):
            self.assets.append(a.get('href', ''))

    def handle_endtag(self, tag):
        if tag in ('script', 'style'):
            self.hidden = max(0, self.hidden - 1)

    def handle_data(self, data):
        if not self.hidden:
            self.text.append(data)


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('base')
    parser.add_argument('--skip-private-files', action='store_true', help='Clean CI has no owner upload volumes')
    args = parser.parse_args()
    base = args.base.rstrip('/') + '/'
    origin = urlsplit(base).netloc
    pages, assets, anchors = {}, set(), []

    def get(url):
        req = Request(url, headers={'X-Forwarded-Proto': 'https', 'User-Agent': 'PortfolioContentAudit/1.0'})
        try:
            with urlopen(req, timeout=25) as response:
                return response.status, response.headers.get_content_type(), response.read()
        except HTTPError as error:
            raise HTTPError(error.url, error.code, f'{error.reason} at {url}', error.headers, error.fp) from None

    def skip_file(url):
        path = urlsplit(url).path
        return args.skip_private_files and (path.startswith('/uploads/') or bool(re.match(r'^/(certificates/.+/(image|download)|documents/\d+/download)$', path)))

    queue = deque([base, urljoin(base, 'blog'), urljoin(base, 'docs'), urljoin(base, 'gallery')])
    while queue:
        url = queue.popleft()
        if url in pages or skip_file(url):
            continue
        status, kind, raw = get(url)
        assert status == 200, (url, status)
        if kind != 'text/html':
            assets.add(url)
            continue
        page = Page()
        html = raw.decode('utf-8')
        page.feed(html)
        visible = ' '.join(page.text)
        assert not re.search(r'lorem ipsum|coming soon|your name|\[(?:placeholder|demo)|placeholder content|demo documentation', visible, re.I), f'Unreviewed content at {url}'
        assert not re.search(r'<(?:title|meta)[^>]*(?:placeholder|lorem ipsum|your name)', html, re.I), f'Unreviewed metadata at {url}'
        assert not re.search(r'batas verifikasi|screenshot pengguna|memaksakan narasi|sesuai informasi saya|fitur terverifikasi|diklaim terverifikasi', visible, re.I), f'Editorial process text at {url}'
        if urlsplit(url).path.startswith(('/blog', '/docs')):
            assert not any(re.search(r'homelab-topology|service-path|/labs/topologies/', asset) for asset in page.assets), f'Topology must stay in Home Lab: {url}'
        pages[url] = page
        for link in page.links:
            assert link.strip() not in ('', '#'), f'Empty link at {url}'
            target, fragment = urldefrag(urljoin(url, link))
            parsed = urlsplit(target)
            if parsed.scheme not in ('http', 'https') or parsed.netloc != origin or parsed.path.startswith('/admin'):
                continue
            if fragment:
                anchors.append((target, fragment))
            if not skip_file(target) and target not in pages:
                queue.append(target)
        for asset in page.assets:
            target = urljoin(url, asset)
            if urlsplit(target).netloc == origin and not skip_file(target):
                assets.add(target)

    for target, fragment in anchors:
        if target in pages:
            assert fragment in pages[target].ids, f'Missing anchor: {target}#{fragment}'
    for asset in sorted(assets):
        status, kind, raw = get(asset)
        assert status == 200 and raw, f'Missing asset: {asset}'
        assert kind != 'text/html', f'Asset returned an HTML page: {asset}'

    for path in ['projects/asset-management-system-placeholder', 'labs/vlan-segmentation-lab-demo', 'docs/mikrotik-basic-configuration-demo', 'blog/getting-started-mikrotik-networking-placeholder']:
        try:
            get(urljoin(base, path))
            raise AssertionError(f'Archived content is still public: {path}')
        except HTTPError as error:
            assert error.code == 404, (path, error.code)

    for path, required in [('blog?q=wg-azure', 'WireGuard'), ('docs?q=192.168.20.199', 'Technitium'), ('blog?category=web-development', 'Docker')]:
        _, _, raw = get(urljoin(base, path))
        assert required in raw.decode('utf-8'), f'Search/filter did not return expected content: {path}'

    assert len([u for u in pages if '/blog/' in u]) >= 6, 'Six blog articles must be reachable'
    assert len([u for u in pages if '/docs/' in u]) >= 6, 'Six configuration documents must be reachable'
    assert len([u for u in pages if '/projects/' in u]) >= 3, 'Three projects must be reachable'
    assert any('/labs/home-lab-azril' in u for u in pages), 'Home lab not reachable'
    print(json.dumps({'pages': len(pages), 'assets': len(assets), 'anchors': len(anchors), 'archived_routes': 4, 'search_filters': 3, 'result': 'passed'}, indent=2))


if __name__ == '__main__':
    main()
