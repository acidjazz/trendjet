/*
 * ssl.js - plugin to check and properly route to ssl
 * Copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 *
 * Distributed under terms of the APACHE license.
 */
export default async function (ctx) {
  if (ctx.env.ENV !== 'local' && ctx.isClient && location.protocol != 'https:') {
    location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
  }
}
