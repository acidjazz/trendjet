/*
 * axios.js - plugin to harness error messages
 * Copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 *
 * Distributed under terms of the APACHE license.
 */

import Vue from 'vue'
import Toast from '@/components/global/Toast/Toast.vue'
import { spawn } from '@/utils/helpers.js'

const ToastProgrammatic = {
  show (props) {
    if (typeof props === 'string') props = { message: props }
    return spawn('toasts', props, Vue, Toast)
  }
}

export default function ({ $axios, app }, inject) {
  inject('toast', ToastProgrammatic)
  $axios.onError(error => {
    const code = parseInt(error.response && error.response.status)
    if (error.response.data && error.response.data.errors) {
      for (let key in error.response.data.errors) {
        for (let index in error.response.data.errors[key]) {
          if (key === 'not.auth') return
          app.$toast.show({ type: 'danger', message: error.response.data.errors[key][index]})
        }
      }
    }
    if (error.response.data && error.response.data.message) {
      app.$toast.show({
        type: 'danger',
        message: `<b>[${error.response.data.exception}]</b> <br /> ${error.response.data.message}`,
        delay: 0,
      })
    }
  })
}

