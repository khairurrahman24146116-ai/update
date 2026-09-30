export function useSeo({ title, description, image, url }) {
  if (title) document.title = title + ' — SMA Madani Al-Aziziyah'
  const set = (sel, attr, val) => {
    if (!val) return
    let el = document.querySelector(sel)
    if (!el) { el = document.createElement('meta'); document.head.appendChild(el) }
    const [k, v] = sel.includes('property') ? ['property', sel.match(/"([^"]+)"/)[1]] : ['name', sel.match(/"([^"]+)"/)[1]]
    el.setAttribute(k, v); el.setAttribute(attr, val)
  }
  set('meta[name="description"]', 'content', description)
  set('meta[property="og:title"]', 'content', title)
  set('meta[property="og:description"]', 'content', description)
  set('meta[property="og:image"]', 'content', image)
  set('meta[property="og:url"]', 'content', url)
}
