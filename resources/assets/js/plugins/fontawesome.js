import Vue from 'vue'
import fontawesome from '@fortawesome/fontawesome'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'

// import { } from '@fortawesome/fontawesome-free-regular/shakable.es'

import {
  faUser, faLock, faSignOutAlt, faCog
} from '@fortawesome/fontawesome-free-solid/shakable.es'

import {
  faGithub, faFacebook, faGoogle
} from '@fortawesome/fontawesome-free-brands/shakable.es'

fontawesome.library.add(
  faUser, faLock, faSignOutAlt, faCog, faGithub, faFacebook, faGoogle
)

Vue.component('fa', FontAwesomeIcon)
