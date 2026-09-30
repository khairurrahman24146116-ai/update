export async function login(identity, password) {
  const r = await fetch('/api/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ identity, email: identity, password }),
  })
  const j = await r.json().catch(() => ({}))
  if (!r.ok) throw { status: r.status, body: j }
  return j
}
export async function me(token) {
  const r = await fetch('/api/me', { headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' } })
  const j = await r.json().catch(() => ({}))
  if (!r.ok) throw { status: r.status, body: j }
  return j
}
export async function logout(token) {
  await fetch('/api/logout', { method: 'POST', headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' } })
}
