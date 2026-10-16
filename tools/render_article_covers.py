"""Compose article artwork from unmodified technology logos kept in brands/."""
from pathlib import Path
from html import escape
from base64 import b64encode

root = Path(__file__).resolve().parents[1] / 'public/media/portfolio'
out = root / 'covers'
out.mkdir(exist_ok=True)
brands = {
    'mikrotik': ('MikroTik', 'mikrotik.svg'),
    'proxmox': ('Proxmox', 'proxmox.svg'),
    'cisco': ('Cisco', 'cisco.svg'),
    'technitium': ('Technitium DNS', 'technitium.png'),
    'debian': ('Debian', 'debian.svg'),
    'docker': ('Docker', 'docker.svg'),
    'npm': ('Nginx Proxy Manager', 'npm.svg'),
    'wireguard': ('WireGuard', 'wireguard.svg'),
    'azure': ('Microsoft Azure', 'azure.svg'),
}
groups = {
    'proxmox-mikrotik': ['proxmox', 'mikrotik'],
    'mikrotik-cisco': ['mikrotik', 'cisco'],
    'technitium': ['technitium'],
    'debian-docker': ['debian', 'docker'],
    'docker-npm': ['docker', 'npm'],
    'wireguard-azure': ['wireguard', 'azure'],
    'network-server': ['mikrotik', 'debian'],
}
for slug, keys in groups.items():
    names = ' dan '.join(brands[key][0] for key in keys)
    s = ['<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="675" viewBox="0 0 1200 675" role="img">',
         f'<title>Logo {escape(names)}</title>',
         '<rect width="1200" height="675" fill="#faf9f6"/>',
         '<rect x="28" y="28" width="1144" height="619" rx="6" fill="#fff" stroke="#e8e2d6"/>',
         '<rect x="72" y="76" width="42" height="3" fill="#c5a059"/>',
         '<text x="72" y="111" font-family="Arial,sans-serif" font-size="15" letter-spacing="3" fill="#77716a">CATATAN JARINGAN &amp; SERVER</text>']
    for i, key in enumerate(keys):
        name, filename = brands[key]
        mime = 'image/svg+xml' if filename.endswith('.svg') else 'image/png'
        data = b64encode((root/'brands'/filename).read_bytes()).decode()
        center = 600 if len(keys) == 1 else [330, 870][i]
        width, height = (380, 150)
        if key in ['technitium', 'azure', 'npm', 'debian', 'cisco']:
            width, height = (150, 150)
        if key == 'technitium': width, height = (80, 80)
        s.append(f'<image href="data:{mime};base64,{data}" x="{center-width/2}" y="{335-height/2}" width="{width}" height="{height}" preserveAspectRatio="xMidYMid meet"/>')
        s.append(f'<text x="{center}" y="475" text-anchor="middle" font-family="Arial,sans-serif" font-size="25" fill="#252525">{escape(name)}</text>')
    s += ['<text x="72" y="598" font-family="Arial,sans-serif" font-size="15" letter-spacing="2" fill="#8a8378">MUHAMMAD AZRIL ACHDI</text>', '</svg>']
    (out/(slug+'.svg')).write_text(''.join(s), encoding='utf-8')
print(f'Generated {len(groups)} SVG covers. Rasterize to matching PNG files for publication.')
