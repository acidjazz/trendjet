import Vue from 'vue'
import Message from './Message.vue'
import { spawn } from '@/utils/helpers.js'

const MessageProgrammatic = {
  show (props) {
    if (typeof props === 'string') props = { message: props }
    return spawn('messages', props, Vue, Message)
  }
}

const Plugin = {
  install(Vue) {
    Vue.prototype['$message'] = MessageProgrammatic
  }
}

Vue.use(Plugin)
export default Plugin


