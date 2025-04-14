class ptsLazyLoad {
    constructor( dataLazyLoadingJS, dataSettings ) {
        this.dataLazyLoadingJS = dataLazyLoadingJS;
        this.dataSettings = dataSettings;
    }
    lazyLoadingJS( type, area ) {
        if (this.dataLazyLoadingJS['data'][type]['status'] === false) {
            this.dataLazyLoadingJS['data'][type]['status'] = true;
            area.append(this.dataLazyLoadingJS['data'][type]['html']);
        }
    }
    loadAllDataScripts() {
        let that = this;
        $.each(this.dataLazyLoadingJS['data'], function (i, el) {
            that.lazyLoadingJS(i, $(el.area));
        });
    }
    showMessage() {
        let that = this;
        $.fancybox({
            content: that.dataSettings.fancybox.content,
            wrapCSS: that.dataSettings.fancybox.wrapCSS,
            helpers: {
                overlay : {closeClick: false}
            },
            beforeClose: function () {
                that.cookieSet();
                that.loadAllDataScripts();
            }
        });
    }
    isSearchSystemBotSigns() {
        let uaList = [
            'APIs-Google', 'Mediapartners-Google', 'AdsBot-Google-Mobile', 'AdsBot-Google', 'Googlebot', 'AdsBot-Google-Mobile-Apps',
            'YandexBot', 'YandexMobileBot', 'YandexDirectDyn', 'YandexScreenshotBot', 'YandexImages', 'YandexVideo', 'YandexVideoParser',
            'YandexMedia', 'YandexBlogs', 'YandexFavicons', 'YandexWebmaster', 'YandexPagechecker', 'YandexImageResizer', 'YandexAdNet',
            'YandexDirect', 'YaDirectFetcher', 'YandexCalendar', 'YandexSitelinks', 'YandexMetrika', 'YandexNews', 'YandexCatalog',
            'YandexMarket', 'YandexVertis', 'YandexForDomain', 'YandexSpravBot', 'YandexSearchShop', 'YandexMedianaBot', 'YandexOntoDB',
            'YandexOntoDBAPI', 'YandexVerticals',
            'Mail.RU_Bot',
            'StackRambler',
            'Yahoo',
            'msnbot',
            'bingbot',
            'PixelTools', 'PixelBot'
        ];
        let referrerList = [
           'http://webvisor.com/'
        ];
        let sBrowser = false, sUsrAg = navigator.userAgent,  referer = document.referrer;
        for (let i = 0; i < uaList.length; i += 1) {
            if (sUsrAg.indexOf(uaList[i]) > -1 || referer.indexOf(referrerList[i])) {
                sBrowser = true;
                break;
            }
        }

        console.log(referer.indexOf(referrerList[i]));

        return sBrowser;
    }
    cookieCheck() {
        console.log(this.dataSettings.cookie_name);
        return $.cookie(this.dataSettings.cookie_name) !== undefined
    }
    cookieSet() {
        $.cookie(this.dataSettings.cookie_name, true, {expires: 365, path: '/'});
    }
    simpleCheck( need_check ) {
        if (+need_check === 1 && !this.cookieCheck() && !this.isSearchSystemBotSigns()) {
            this.showMessage();
        } else {
            this.loadAllDataScripts();
        }
    }
}