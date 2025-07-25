let headerCatalogWrap = document.querySelector('.header__menu');
let headerMenuWrap = document.querySelector('.header__main-menu-wrap');
let headerMenuItems = document.querySelectorAll('.header__main-menu-item');
let subMenues = document.querySelectorAll('.header__main-submenu');
let imageCatId = [];
let menuLinksWithImage = headerCatalogWrap.querySelectorAll('.header__main-submenu-item_image');
let imageLinks;
let timerId;

function openHeaderCatalog () {
    timerId = setTimeout(() => {
        headerCatalogWrap.classList.add('header__menu_open');
    }, 500)
}

function closeHeaderCatalog () {
    clearTimeout(timerId);
    headerCatalogWrap.classList.remove('header__menu_open');
}

function showSubmenu(event) {
    let menu = event.target.getAttribute('data-menu');
    let menuSubMenu = document.querySelector(`.header__main-submenu[data-menu='${menu}']`);
    menuSubMenu.classList.add('header__main-submenu_show');
}

function closeAllSubmenu() {
    subMenues.forEach((el) => {
        el.classList.remove('header__main-submenu_show');
    })
}

function getCatImagesAjax () {
    menuLinksWithImage.forEach((link)=>{
        imageCatId.push(link.getAttribute('data-cat_id'));
    })

    const data = new URLSearchParams();
    data.append('action', 'get_menu_cats_image');
    data.append('cat_id', imageCatId);

    fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => {
        if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        console.debug('✅ AJAX success:', result);
        if (result.success) {
            imageLinks = result.data.image_url;
        }
    })
    .catch(error => {
        console.error('❌ AJAX error:', error);
    })
    .finally(() => {
        console.debug('⚙️ AJAX complete');
    });
}

function getCatImage(event, catId) {
    let menuImage = event.target.closest('.header__main-submenu').querySelector('.header__main-submenu-img');
    if (imageLinks && menuImage) {
        let imageLink = imageLinks[`id_${catId}`];
        if(imageLink) {
            menuImage.setAttribute('src',imageLink);
        }
    }
}

function getDefaultImage(event) {
    let menuImage = event.target.closest('.header__main-submenu').querySelector('.header__main-submenu-img');
    menuImage.setAttribute('src','https://plantis.shop/wp-content/uploads/2025/06/интерьер.webp');
}


headerMenuItems.forEach((el) => {
    if(el.getAttribute('data-menu')) {
        el.addEventListener('mouseenter', openHeaderCatalog);
        el.addEventListener('mouseenter', closeAllSubmenu);
        el.addEventListener('mouseenter', showSubmenu);
    }
    if(!el.getAttribute('data-menu')) {
        el.addEventListener('mouseenter', closeHeaderCatalog);
    }
    el.addEventListener('click', closeHeaderCatalog);
})


headerMenuWrap.addEventListener('mouseleave', closeHeaderCatalog);

menuLinksWithImage.forEach((el)=>{
    let catId = el.getAttribute('data-cat_id');
    if(catId) {
        el.addEventListener('mouseenter',(evt)=>{getCatImage(evt,catId)})
        el.addEventListener('mouseleave', (evt)=>{getDefaultImage(evt)})
    }
})

headerMenuWrap.addEventListener('mouseenter',getCatImagesAjax,{once:true});


