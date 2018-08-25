/*
 * auth.js - middleware to check and properly store the logged in user
 * Copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 *
 * Distributed under terms of the APACHE license.
 */
export default async function (ctx) {
  if (ctx.store.state.user !== null || process.server) {
    return true
  } else {

    let blocked = ['login-id']
    if (blocked.indexOf(ctx.route.name) !== -1) return true

    try {
      const result = await ctx.$axios.get('/me')
      if (result.data && result.data.data) {
        ctx.store.commit('user', result.data.data)
        return true
      }
    } catch (e) {
      console.log('middleware auth', e)
      return true
    }

  }
}
