/*
 * envs.js
 * Copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 *
 * Distributed under terms of the APACHE license.
 */
export default {
  data () {
    return {
      ENV: process.env.ENV,
      API_URL: process.env.API_URL,
      GOOGLE_API_KEY: process.env.GOOGLE_API_KEY,
    }
  }
}
