import { register, init, getLocaleFromNavigator } from 'svelte-i18n';

register('en', async () => {
    console.log('looading english');
    const p = new Promise((resolve) => {
        setTimeout(() => {
            resolve('done');
        }, 1)
    })
    const result = import('./locales/en.json');

    const results = await Promise.all([result, p]);
    
    console.log('result', results);
    return results[0];
});
// register('en-US', () => import('./locales/en-US.json'));

console.log(getLocaleFromNavigator());
init({
    fallbackLocale: 'en',
    initialLocale: getLocaleFromNavigator(),
  });