export function user (state) {
  return state.user
}

export function loggedIn (state) {
  return state.user !== null
}
