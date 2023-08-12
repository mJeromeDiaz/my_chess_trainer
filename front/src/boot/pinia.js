import { boot } from 'quasar/wrappers';
import { createPinia } from 'pinia'

const pinia = createPinia()
console.log('🍍 est instancié')

export default boot(({app}) => {
    app.use(pinia)
});

export { pinia }
