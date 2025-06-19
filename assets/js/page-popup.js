//переменные для управления попапом
const pageOpenPopupBtn = document.querySelectorAll('.page-popup-open-btn');
const pagePopup = document.querySelector('.page-popup');
const pageClosePopupBtn = document.querySelector('.page-popup__close');
const pagePopupOverlay = document.querySelector('.page-popup__popup-overlay');

//для попапа на странице усуги по уходу
let serviceButtons = document.querySelectorAll('.service-page .page-popup-open-btn');
let serviceNameInput = document.querySelector('.service-popup-service-name');
//let ukhodClosePopupBtn = document.querySelector('.service-popup .page-popup__close');
let serviceContactForm = document.querySelector('.service-popup form');

if (pagePopup != null && pageOpenPopupBtn != null) {
    pageOpenPopupBtn.forEach(button => {
      button.addEventListener ("click", (evt)=>{
          toggle_page_popup ();
      });
    });
    
    if (pageClosePopupBtn) {
        pageClosePopupBtn.addEventListener ("click", (evt)=>{
            toggle_page_popup ();
            cleanUkhodForm();
        });
    }

    pagePopupOverlay.addEventListener ("click", (evt)=>{
        toggle_page_popup ();
        cleanUkhodForm();
    });

    document.addEventListener('keydown', function(e){
        if((e.key=='Escape'||e.key=='Esc')){
            if(pagePopup.classList.contains('popup_active')) {
                toggle_page_popup ();
                cleanUkhodForm();
            } 
        }
    }, true);
}

function toggle_page_popup () {
    pagePopup.classList.toggle ('popup_active');
    body.classList.toggle ('fix-body');
};


if(pagePopup != null && serviceButtons != null) {
  serviceButtons.forEach(btn => {
    btn.addEventListener('click', (evt) => {
      if(serviceNameInput != null) {
        serviceNameInput.setAttribute('value',evt.target.name);
        console.log(serviceNameInput);
      }
    });
  });
}

function cleanUkhodForm() {
  if(serviceContactForm != null) {
    serviceContactForm.reset();
    // if(serviceNameInput != null) {
    //   serviceNameInput.setAttribute('value','');
    //   serviceContactForm.reset();
    //   console.log(serviceNameInput);
    // }
  }
}


