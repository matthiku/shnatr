import Vue from 'vue'
import fontawesome from '@fortawesome/fontawesome'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'

// import { } from '@fortawesome/fontawesome-free-regular/shakable.es'

import {
  faUser,
  faUsers,
  faLock,
  faSignOutAlt,
  faCog,
  faMobileAlt,
  faCheck,
  faComments,
  faSearch,
  faTrashAlt,
  faUserSlash,
  faPaperclip,
  faSmile,
  faHome
} from '@fortawesome/fontawesome-free-solid/shakable.es'

import {
  faGithub, faFacebook, faGoogle
} from '@fortawesome/fontawesome-free-brands/shakable.es'

fontawesome.library.add(
  faUser,
  faUsers,
  faLock,
  faSignOutAlt,
  faCog,
  faGithub,
  faFacebook,
  faGoogle,
  faMobileAlt,
  faCheck,
  faComments,
  faSearch,
  faTrashAlt,
  faUserSlash,
  faPaperclip,
  faSmile,
  faHome
)

Vue.component('fa', FontAwesomeIcon)
