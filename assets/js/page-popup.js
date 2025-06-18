//переменные для управления попапом
const pageOpenPopupBtn = document.querySelectorAll('.page-popup-open-btn');
const pagePopup = document.querySelector('.page-popup');
const pageClosePopupBtn = document.querySelector('.page-popup__close');
const pagePopupOverlay = document.querySelector('.page-popup__popup-overlay');

//для попапа на странице усуги по уходу
let ukhodButtons = document.querySelectorAll('.page-ukhod .page-popup-open-btn');
let serviceNameInput = document.querySelector('.ukhod-popup-service-name');
//let ukhodClosePopupBtn = document.querySelector('.ukhod-popup .page-popup__close');
let ukhodContactForm = document.querySelector('.ukhod-popup form');

console.log(ukhodContactForm);


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


if(pagePopup != null && ukhodButtons != null) {
  ukhodButtons.forEach(btn => {
    btn.addEventListener('click', (evt) => {
      if(serviceNameInput != null) {
        serviceNameInput.setAttribute('value',evt.target.name);
        console.log(serviceNameInput);
      }
    });
  });
}

function cleanUkhodForm() {
  if(ukhodContactForm != null) {
    ukhodContactForm.reset();
    // if(serviceNameInput != null) {
    //   serviceNameInput.setAttribute('value','');
    //   ukhodContactForm.reset();
    //   console.log(serviceNameInput);
    // }
  }
}


