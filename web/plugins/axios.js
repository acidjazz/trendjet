/*
 * axios.js - plugin to harness error messages
 * Copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 *
 * Distributed under terms of the APACHE license.
 */

import Vue from 'vue'
import Message from '@/components/global/Message/Message.vue'
import { spawn } from '@/utils/helpers.js'

const MessageProgrammatic = {
  show (props) {
    if (typeof props === 'string') props = { message: props }
    return spawn('messages', props, Vue, Message)
  }
}

export default function ({ $axios, app }, inject) {
  inject('message', MessageProgrammatic)
  $axios.onError(error => {
    const code = parseInt(error.response && error.response.status)
    if (error.response.data && error.response.data.errors) {
      for (let key in error.response.data.errors) {
        for (let index in error.response.data.errors[key]) {
          app.$message.show({ type: 'danger', message: error.response.data.errors[key][index]})
        }
      }
    }
    if (error.response.data && error.response.data.message) {
      app.$message.show({
        type: 'danger', 
        message: `<b>[${error.response.data.exception}]</b> ${error.response.data.message}`,
      })
    }
  })
}

