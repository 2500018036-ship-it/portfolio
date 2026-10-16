from pathlib import Path
from html import escape

out=Path(__file__).resolve().parents[1] / 'public/media/portfolio'
ink='#191b20'; gold='#a8823f'; muted='#646b75'; line='#cbd0d6'

def start(w,h,title,desc):
 return [f'<svg xmlns="http://www.w3.org/2000/svg" width="{w}" height="{h}" viewBox="0 0 {w} {h}" role="img" aria-labelledby="title desc"><title id="title">{escape(title)}</title><desc id="desc">{escape(desc)}</desc><rect width="100%" height="100%" fill="#fcfcfc"/><defs><marker id="arrow" markerWidth="8" markerHeight="8" refX="7" refY="4" orient="auto-start-reverse"><path d="M0 0L8 4L0 8Z" fill="#a8823f"/></marker></defs><g font-family="Arial, sans-serif">']
def text(x,y,s,size=18,fill=ink,weight=400):
 return f'<text x="{x}" y="{y}" font-size="{size}" fill="{fill}" font-weight="{weight}">{escape(s)}</text>'
def box(x,y,w,h,title,lines=(),accent=False):
 s=f'<rect x="{x}" y="{y}" width="{w}" height="{h}" rx="12" fill="{"#faf6ec" if accent else "#fff"}" stroke="{gold if accent else line}" stroke-width="1.5"/>'+text(x+22,y+34,title,22,ink,700)
 for i,t in enumerate(lines): s+=text(x+22,y+65+i*26,t,17,muted)
 return s
def edge(path,dash=False,color=gold):
 return f'<path d="{path}" fill="none" stroke="{color}" stroke-width="2.5" marker-end="url(#arrow)"'+(' stroke-dasharray="8 7"' if dash else '')+'/>'

s=start(1600,1500,'Topologi Home Lab Muhammad Azril Achdi','ONT ke MikroTik; trunk ether4 ke GE1 Cisco SF200-24; tiga VLAN; Proxmox, Debian, Technitium; akses Azure WireGuard dan Tailscale dengan batas verifikasi.')
s += [text(60,55,'MUHAMMAD AZRIL ACHDI / HOME LAB',16,gold,700),text(60,103,'Jaringan, server, dan jalur akses',36,ink,700),text(60,137,'Konfigurasi pribadi • dicocokkan pada 16 Oktober 2026',18,muted)]
# Connections are drawn before nodes so the endpoints remain clean.
s += [edge('M 800 285 V 330',True),edge('M 1010 410 H 1120 V 270 H 1160',True),edge('M 800 500 V 590'),edge('M 800 725 V 765 H 285 V 815'),edge('M 800 725 V 815'),edge('M 800 765 H 1315 V 815'),edge('M 800 945 V 1005'),edge('M 285 945 V 1005'),edge('M 1315 945 V 1005')]
s += [box(610,185,380,100,'ONT / Upstream ISP',['LAN → ether1 MikroTik (sketsa)']),box(1160,185,380,150,'Azure VPS',['20.194.8.143:51820 (backup)','Peer dirujuk: 10.10.0.1','Nginx HTTPS → WireGuard'],True),box(550,330,500,170,'MikroTik — gateway VLAN',['10: 192.168.10.1 / 20: 192.168.20.1','30: 192.168.30.1','wg-azure: 10.10.0.2/24'],True)]
s += [text(825,547,'ether4 → GE1',18,gold,700),text(825,575,'802.1Q: VLAN 10 / 20 / 30 tagged',17,muted),box(550,590,500,135,'Cisco SF200-24',['GE1 trunk: 1UP, 10T, 20T, 30T','Native VLAN / PVID: 1'],True)]
s += [text(105,791,'FE1 / FE2 • access VLAN 10',17,gold,700),text(640,791,'GE2 • access VLAN 20',17,gold,700),text(1140,791,'FE3 • access VLAN 30',17,gold,700),box(60,815,450,130,'VLAN 10 — Management',['192.168.10.0/24 • gateway .1','Pool DHCP: 192.168.10.100–200']),box(575,815,450,130,'VLAN 20 — Server',['192.168.20.0/24 • gateway .1','Pool DHCP: 192.168.20.100–200']),box(1090,815,450,130,'VLAN 30 — Client / guest',['192.168.30.0/24 • gateway .1','Pool DHCP: 192.168.30.100–200'])]
s += [box(60,1005,450,145,'PC management',['Akses perangkat sesuai kebijakan router','Cisco dirujuk backup: 192.168.10.2','Alamat PC tidak ditetapkan di diagram']),box(1090,1005,450,145,'Access Point TP-Link',['Cabang AP mengikuti sketsa pengguna','Client / guest pada segmen VLAN 30','Model dan IP AP belum diverifikasi'])]
s += [f'<rect x="550" y="985" width="500" height="330" rx="16" fill="#faf6ec" stroke="{gold}" stroke-width="1.5"/>',text(575,1020,'Proxmox VE • 192.168.20.200:8006',21,ink,700),text(575,1049,'Cabang virtualisasi mengikuti sketsa pribadi',16,muted),box(575,1070,450,92,'Technitium DNS',['192.168.20.199 • DNS :53 / panel :5380']),box(575,1180,450,110,'Debian Web Server',['192.168.20.195/24 • ens18 • DHCP','Docker + Nginx Proxy Manager'])]
s += [box(60,1190,450,125,'Tailscale — akses pribadi',['Node, ACL, dan subnet route belum diperiksa','tailscaled tidak aktif di Debian saat audit'],False)]
s += [text(60,1370,'MEMBACA DIAGRAM',15,gold,700),text(60,1400,'Garis utuh: pemetaan VLAN/perangkat yang dicocokkan. Garis putus: jalur logis atau hubungan dari sketsa/dokumentasi.',17,muted),text(60,1430,'Sumber: sketsa pribadi, screenshot Cisco, backup MikroTik, SSH Debian, endpoint Proxmox/Technitium, dan dokumentasi deployment.',16,muted),text(60,1460,'VM ID, bridge Proxmox, model AP, node Tailscale, serta status firewall aktif tidak diasumsikan. Kunci VPN dan backup asli tidak dipublikasikan.',16,muted)]
s.append('</g></svg>')
(out/'homelab-topology.svg').write_text(''.join(s),encoding='utf-8')

s=start(1600,930,'Jalur akses website portofolio','Internet HTTPS ke Azure Nginx, WireGuard ke MikroTik, Debian Nginx Proxy Manager, container CodeIgniter dan MariaDB. DNS lokal Technitium terpisah dari jalur HTTP.')
s += [text(60,55,'MUHAMMAD AZRIL ACHDI / SERVICE PATH',16,gold,700),text(60,103,'Dari domain publik ke aplikasi lokal',36,ink,700),text(60,140,'azrilachdi.web.id • alur deployment yang digunakan portofolio',18,muted)]
s += [edge('M 405 270 H 560'),edge('M 1040 270 H 1170',True),edge('M 1345 345 V 410 H 800 V 470',True),edge('M 1040 555 H 1160'),edge('M 1345 650 V 720'),edge('M 340 555 H 560',True)]
s += [box(60,210,345,120,'Pengunjung website',['azrilachdi.web.id','HTTPS :443']),box(560,195,480,150,'Nginx pada Azure VPS',['DNS publik: 20.194.8.143','Terminasi TLS (dokumen deployment)','Host + X-Forwarded-Proto: https'],True),box(1170,195,370,150,'MikroTik / WireGuard',['wg-azure: 10.10.0.2/24','Peer VPS: 10.10.0.1','Endpoint backup UDP :51820'],True)]
s += [text(1065,248,'tunnel',17,gold,700),box(60,470,280,180,'Technitium DNS',['192.168.20.199:53','Resolver Debian via DHCP','Panel lokal :5380','Terpisah dari jalur HTTP']),box(560,470,480,180,'Debian + Nginx Proxy Manager',['Debian: 192.168.20.195','NPM host :80 / :81 / :443','Proxy host → portfolio-web:80','Network: web-proxy'],True),box(1160,470,380,180,'Container portofolio',['CodeIgniter 4 • PHP 8.3 • Apache','Origin host: 127.0.0.1:18081','Volume unggahan & writable']),box(1160,720,380,115,'MariaDB 11.4',['Network backend internal','Tanpa port database pada host'])]
s += [box(60,720,980,115,'Akses administrasi pribadi',['Tailscale digunakan sesuai informasi pengguna; node/ACL/rute belum diperiksa.','SSH Debian tersedia di jaringan lokal pada 192.168.20.195:22.'])]
s += [text(60,887,'Sumber: backup MikroTik, DNS yang diamati, dokumentasi deploy, Compose, proxy host aktif, dan pemeriksaan SSH Debian.',17,muted)]
s.append('</g></svg>')
(out/'service-path.svg').write_text(''.join(s),encoding='utf-8')
print('Generated two accessible SVG diagrams.')
