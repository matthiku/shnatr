const Welcome = () => import('~/pages/welcome').then(m => m.default || m)
const Login = () => import('~/pages/auth/login').then(m => m.default || m)
const Register = () => import('~/pages/auth/register').then(m => m.default || m)
const PasswordEmail = () => import('~/pages/auth/password/email').then(m => m.default || m)
const PasswordReset = () => import('~/pages/auth/password/reset').then(m => m.default || m)
const NotFound = () => import('~/pages/errors/404').then(m => m.default || m)

const Home = () => import('~/pages/home').then(m => m.default || m)

const Chat = () => import('~/pages/chat/index').then(m => m.default || m)
const ChatUsers = () => import('~/pages/chat/users').then(m => m.default || m)
const ChatRooms = () => import('~/pages/chat/rooms').then(m => m.default || m)
const ChatRoom = () => import('~/pages/chat/room').then(m => m.default || m)

const Settings = () => import('~/pages/settings/index').then(m => m.default || m)
const SettingsProfile = () => import('~/pages/settings/profile').then(m => m.default || m)
const SettingsPassword = () => import('~/pages/settings/password').then(m => m.default || m)

export default [
  { path: '/', name: 'welcome', component: Welcome },

  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Register },
  { path: '/password/reset', name: 'password.request', component: PasswordEmail },
  { path: '/password/reset/:token', name: 'password.reset', component: PasswordReset },

  { path: '/home', name: 'home', component: Home },

  { path: '/chat',
    component: Chat,
    children: [
      { path: '', redirect: { name: 'chat.rooms' } },
      { path: 'users', name: 'chat.users', component: ChatUsers },
      { path: 'rooms', name: 'chat.rooms', component: ChatRooms },
      { path: 'room/:room_id?', name: 'chat.room', component: ChatRoom, props: true }
    ] },

  { path: '/settings',
    component: Settings,
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: SettingsProfile },
      { path: 'password', name: 'settings.password', component: SettingsPassword }
    ] },

  { path: '*', component: NotFound }
]
