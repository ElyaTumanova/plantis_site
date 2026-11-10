document.addEventListener('DOMContentLoaded', () => {
	const notice = document.getElementById('cookie-notice');

	if (!notice) return;

	const cookieName = 'plnt_cookie_accepted';

	const accepted = document.cookie
		.split('; ')
		.some(cookie => cookie.startsWith(`${cookieName}=`));

	if (accepted) return;

	notice.hidden = false;

	notice.querySelector('.cookie-notice__button')
		.addEventListener('click', () => {
			document.cookie = `${cookieName}=1; path=/; max-age=15552000; SameSite=Lax${location.protocol === 'https:' ? '; Secure' : ''}`;

			notice.hidden = true;
		});
});