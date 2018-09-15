/*
 * is-auth.js - middleware to redirect non-logged in users
 * Copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 *
 * Distributed under terms of the APACHE license.
 */
export default async function ({ store, redirect }) {
  if (store.state.user === null) {
    return redirect('/')
  }
}
