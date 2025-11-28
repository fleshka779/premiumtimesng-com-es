
// banner
let banner = document.querySelector('.banner');

if(banner){
    let bannerClose = banner.querySelector('.close');

    bannerClose.addEventListener('click', function(){
        banner.classList.remove('open');
    });
}
/* // bonus
let bonus = document.querySelector('.bonus');
 
if(bonus){
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
// navigation mob
let menuMob = document.querySelector('.navigation');

if(menuMob){
	let btnMenu = document.querySelector('.hamburger'),
		menuMobClose = menuMob.querySelector('.close'),
		mobNavA = menuMob.querySelectorAll('a'),
		mobItem = menuMob.querySelectorAll('.menu-arr'),
		overly = document.querySelector('.overly'),
		body = document.body;

		btnMenu.addEventListener('click', function(){
			menuMob.classList.add('open');
			this.setAttribute('aria-expanded', true);
			overly.style.display = "block";
			body.classList.add('body_scroll');
		})

		menuMobClose.addEventListener('click', function(){
			menuMob.classList.remove('open');
			btnMenu.setAttribute('aria-expanded', false);
			overly.style.display = "none";
			body.classList.remove('body_scroll');
		})

		mobItem.forEach(function(el){
			let menuBtn = el.querySelector('.menu-btn');

				menuBtn.addEventListener('click', function(){
					if(el.classList.contains('open')){
						this.setAttribute('aria-expanded', false);
					} else {
						this.setAttribute('aria-expanded', true);
					}

					el.classList.toggle('open');
				});

		});
	

		overly.addEventListener('click', function(){
			this.style.display = "none";
			menuMob.classList.remove('open');
			body.classList.remove('body_scroll');
			btnMenu.setAttribute('aria-expanded', false);
		});


		mobNavA.forEach(function(el){
			el.addEventListener('click', function(){
				overly.style.display = "none";
				menuMob.classList.remove('open');
				body.classList.remove('body_scroll');
				btnMenu.setAttribute('aria-expanded', false);
			});
		});
}
// rating
if(document.querySelector('.rating')){
    let rating = document.querySelectorAll('.rating');
        
        rating.forEach(function(el){
            let ratingText = el.querySelector('.rating-total-span').innerHTML,
                ratingSpan = el.querySelector('.rating-span'),
                //ratingTotal = (ratingText.replace(/[./,]/g, "")) + '0';
					 ratingTotal = (parseFloat(ratingText) * 10);
					 console.log(ratingTotal, ratingText, parseFloat(ratingText));

                ratingSpan.style.width = ratingTotal + '%';
        });
}
// scroll up
let btnUp = document.getElementsByClassName('btn-up')[0];


if(btnUp){
    function scrollUp(){
      return window.pageYOffset || document.documentElement.scrollTop;
    }
  
    window.addEventListener('scroll', function(){
      if(scrollUp() >= 100){
        btnUp.style.display = 'block';
      } else {
        btnUp.style.display = 'none';
      }
  
    });
  
    btnUp.addEventListener('click', function scrolltoTop(){
      window.scrollBy(0,-150);
      if(scrollUp() > 0){
        requestAnimationFrame(scrolltoTop);
      } 
    });
}
//# sourceMappingURL=../sourcemaps/js.js.map
