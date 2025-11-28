
document.addEventListener('DOMContentLoaded', () => {
		
	// bonus
	let bonus = document.querySelector('.bonus');

	/* if(bonus){
		let bonusBg = document.querySelector('.bonus-bg'),
			body = document.body,
			bonusClose = bonus.querySelector('.close');

			setTimeout(function(){
				bonus.classList.add('open'); 
				body.classList.add('body_scroll');
			}, 10000)

			bonusClose.addEventListener('click', function(){
				bonus.classList.remove('open');
				body.classList.remove('body_scroll');
			})

			bonusBg.addEventListener('click', function(){
				bonus.classList.remove('open');
				body.classList.remove('body_scroll');
			})
	} */

	if(bonus){
		let bonusBg = document.querySelector('.bonus-bg'),
			body = document.body,
			bonusClose = bonus.querySelector('.close');
		
		const popupSelector = '.bonus'; // селектор твого поп-апа
		const showDelay = 10 * 1000; // 10 секунд до першого показу
		const showInterval = 10 * 60 * 1000; // 10 хвилин між показами

		const popup = bonus ;

		const lastShown = localStorage.getItem('popup_last_shown');
		const now = Date.now();
	
		const showPopup = () => {
			
			bonus.classList.add('open'); 
			body.classList.add('body_scroll');

			localStorage.setItem('popup_last_shown', Date.now());
		};

		bonusClose.addEventListener('click', function(){
			bonus.classList.remove('open');
			body.classList.remove('body_scroll');
		})

		bonusBg.addEventListener('click', function(){
			bonus.classList.remove('open');
			body.classList.remove('body_scroll');
		})

	
		if (!lastShown) {		
			setTimeout(showPopup, showDelay);
		} else {
			const diff = now - parseInt(lastShown, 10);

			if (diff > showInterval) {
				
				setTimeout(showPopup, showDelay);
			} else {
				
			}
		}
	}
});