//Класс, который представляет сам тест

class Test {
  constructor(questions, plantTypes)
   {
      //Массив с вопросами
      this.questions = questions;
      this.plantTypes = plantTypes;

      this.testWrap = document.querySelector('.test');
      this.progressBar = document.querySelector('#progress');

      this.questionForm = document.querySelector('.test__answers-form');
 
      //Индекс текущего вопроса
      this.current = 0;

      this.testError = document.querySelector('.test__error');
    
      this.testResult = document.querySelector('.test__result');
      this.testResultName = document.querySelector('.test__result-name span');
      this.testResultDescr = document.querySelector('.test__result-descr');
      this.copyShareBtn = document.querySelector('#copyShareBtn');
      this.testShareTelegram = document.querySelector('.test__result .social-media__button-telegram');
      this.testShareWhatsapp = document.querySelector('.test__result .social-media__button-whatsapp');
      this.testShareOk = document.querySelector('.test__result .social-media__button-ok');
      this.testShareVk = document.querySelector('.test__result .social-media__button-vk');
      this.testResultImage = document.querySelector('.test__result-image');
   }

   testInit() {
    this.questions[this.current].renderQuestion();
    this.questionForm.addEventListener('submit', ()=>{this.handleFormSubmit()});
    console.log(this);
   }

    handleFormSubmit() {
      event.preventDefault();
      if (this.questions[this.current].chosenAnswer) {
        this.questions[this.current].chosenAnswer.countScore();

        ++this.current;
        console.log(this.current);
        if(this.current < this.questions.length) {
          this.questions[this.current].renderQuestion();
          this.progressBar.value = this.current;
        } else {
          this.cleanQuestion();
          this.questionForm.classList.add('d-none');
          this.showResult();
        }
      } else {
        this.testError.classList.add('test__error_show');
      }
    }

    cleanQuestion() {
      this.testWrap.remove();
    }

    showResult() {
        console.log(this.plantTypes);

        this.resultPlant = plantTypes.reduce(function(prev, current) {
            if (+current.score > +prev.score) {
                return current;
            } else {
                return prev;
            }
        });

        console.log(this.resultPlant);
        //link to share
        const shareText = 'Посмотри какое я растение';
        const pageUrl = new URL('/test-result', vars.site_url); // vars.site_url = 'https://dev.plantis-shop.ru'
        pageUrl.searchParams.set('plant', this.resultPlant.slug); // 'zamiokulkas'
        pageUrl.searchParams.set('gen', gen);                     // 'f'
        this.resultPageUrl = pageUrl.toString();
        console.log(this.resultPageUrl);

        const tg = new URL('https://t.me/share/url');
        tg.searchParams.set('url', this.resultPageUrl);
        tg.searchParams.set('text', shareText);
        this.testShareTelegram.setAttribute('href',tg.toString());

        const wa = new URL('https://web.whatsapp.com/send');
        wa.searchParams.set('text', `${shareText} - ${pageUrl}`);
        this.testShareWhatsapp.setAttribute('href', wa.toString());
    
        const ok = new URL('https://connect.ok.ru/offer');
        ok.searchParams.set('url', pageUrl);
        ok.searchParams.set('title', shareText);
        this.testShareOk.setAttribute('href', ok.toString());

        const vk = new URL('https://vk.com/share.php');
        vk.searchParams.set('url', pageUrl);
        vk.searchParams.set('title', shareText);
        this.testShareVk.setAttribute('href', vk.toString());

        this.testResult.classList.remove('d-none');
        this.testResultName.innerText = `Вы ${this.resultPlant.name}!`;
        this.testResultDescr.innerText = this.resultPlant.result;
        this.copyShareBtn.dataset.url = this.resultPageUrl;
        this.testResultImage.setAttribute('src',this.resultPlant.image);
        this.testResultImage.setAttribute('alt',this.resultPlant.name);

        ajaxGetUpsells(this.resultPlant.slug);
    }
}

//Класс, представляющий вопрос
class Question
{
  constructor(text, answers, image)
  {
    this.text = text;
    this.answers = answers;
    this.image = image;
    this.questionImage = document.querySelector('.test__image');
    this.questionElement = document.querySelector('.test__question');
    this.answersList = document.querySelector('.test__answers');
    this.testError = document.querySelector('.test__error');
    this.chosenAnswer = this.chosenAnswer;
  }
 
  renderQuestion() {
    this.answersList.innerHTML = "";
    this.questionElement.innerText = this.text;
    this.questionImage.setAttribute('src',this.image);
    this.questionImage.setAttribute('alt',this.text);


    this.answers.forEach(answer => {
      console.log(answer);
      this.answerElementDiv = document.createElement('div');
      this.answerElementDiv.classList.add('test__answer');
      this.answerElementInput = document.createElement('input');
      this.answerElementLabel = document.createElement('label');
      this.answerElementLabel.setAttribute('for', answer.type.slug);
      this.answerElementInput.setAttribute('type', 'radio');
      this.answerElementInput.setAttribute('name', 'answer');
      this.answerElementInput.setAttribute('id', answer.type.slug); 
      this.answerElementLabel.innerText = answer.text;
      this.answerElementDiv.appendChild(this.answerElementInput);
      this.answerElementDiv.appendChild(this.answerElementLabel);
      this.answersList.appendChild(this.answerElementDiv);
      this.answerElementInput.addEventListener('click', ()=>{this.handleInputClick(answer)});
    })
  }
  
  handleInputClick(answer, answerElementDiv) {
    this.chosenAnswer = answer;
    console.log(answer);
    console.log(answerElementDiv);
    this.testError.classList.remove('test__error_show');
  }
}
 
//Класс, представляющий ответ
class Answer
{
   constructor(text, type)
   {
       this.text = text;
       this.type = type;
   }

   countScore () {
     //console.log('answer type is ', this.type.name)
     //console.log('answer score is ', this.type.score)
     
     ++this.type.score;
     
     //console.log('answer score is ', this.type.score)
   }
}

//console.log(plantTypes);

//Массив с вопросами
const questions = [
   new Question("Какой вид отдыха тебе ближе всего?",
    [
        new Answer("Сидеть дома, обложившись пледами, сериалами и заказной едой – идеальный уют.", plantTypes[9]),
        new Answer("Уехать на пляж, надеть солнечные очки и забыть о делах.", plantTypes[2]),
        new Answer("Отправиться в спонтанное приключение или начать ремонт. Ну, типа, для души.", plantTypes[6]),
        new Answer("Провести день в одиночестве, читая философские трактаты под кружку чая.", plantTypes[3])
    ],
    vars.theme_url + '/images/test/q_1.webp'
   ),

   new Question("Как ты ведёшь себя на вечеринке?",
    [
        new Answer("Я в центре внимания, делаю эффектный вход, все обсуждают мой лук.", plantTypes[5]),
        new Answer("Я сижу в уголке, наблюдаю и делаю умные выводы.", plantTypes[8]),
        new Answer("Завожу душевные разговоры и внимательно слушаю других.", plantTypes[1]),
        new Answer("Я там, где весело, но всегда готов(а) сбежать по-тихому, если станет скучно.", plantTypes[7])
    ],
    vars.theme_url + '/images/test/q_2.webp',
  ),

   new Question("Твоя реакция на критику:",
   [
       new Answer("Саркастично улыбаюсь и делаю вид, что мне всё равно (но я запомнил).", plantTypes[4]),
       new Answer("Очень переживаю, но потом благодарю — ведь это помогает расти.", plantTypes[1]),
       new Answer("Критику можно? Только тихо и желательно не сегодня.", plantTypes[3]),
       new Answer("Критика — просто шум. Я выше этого.", plantTypes[0])
   ],
    vars.theme_url + '/images/test/q_3.webp'
  ),

   new Question("Какой стиль тебе ближе всего?",
   [
       new Answer("Строгость и лаконичность, но с изюминкой.", plantTypes[7]),
       new Answer("Цветные узоры, аксессуары и немного аромапалочек.", plantTypes[6]),
       new Answer("Ярко, модно, немного экстравагантно.", plantTypes[5]),
       new Answer("Классика: монохром, минимализм, аккуратность.", plantTypes[4])
   ],
    vars.theme_url + '/images/test/q_4.webp'
  ),

   new Question("Как ты относишься к переменам?",
   [
       new Answer("Перемен не боюсь, но делаю всё на своих условиях.", plantTypes[0]),
       new Answer("Люблю перемены, особенно если они влекут за собой новые горизонты!", plantTypes[7]),
       new Answer("Немного напрягаюсь, но адаптируюсь быстро.", plantTypes[9]),
       new Answer("Перемены? Я сначала распускаю пару листьев тревоги.", plantTypes[3])
   ],vars.theme_url + '/images/test/q_5.webp'),

   new Question("Какой твой идеальный рабочий день?",
   [
       new Answer("Всё по плану, без стресса, с ароматом кофе и лёгким дзеном.", plantTypes[6]),
       new Answer("Периоды продуктивности сменяются моментами созерцания.", plantTypes[3]),
       new Answer("Много общения, обсуждений, комплиментов и немного работы.", plantTypes[5]),
       new Answer("Рабочий день? Главное, чтобы никто не мешал.", plantTypes[8])
   ],vars.theme_url + '/images/test/q_6.webp'),

   new Question("Что ты обычно делаешь в свободное время?",
   [
       new Answer("Составляю планы на неделю и на случай апокалипсиса.", plantTypes[4]),
       new Answer("Валяюсь, медитирую, сижу в тёплом углу.", plantTypes[0]),
       new Answer("Встречаюсь с друзьями, устраиваю мини-праздники без повода.", plantTypes[2]),
       new Answer("Украшаю дом, настраиваю уют, делаю что-то красивое.", plantTypes[9])
   ],vars.theme_url + '/images/test/q_7.webp'),

   new Question("Тебя забыли поздравить с праздником. Как ты реагируешь?",
   [
       new Answer("Делаю вид, что не обиделся, но потом драматично намекаю.", plantTypes[5]),
       new Answer("Мне не нужны поводы — я сам себе праздник.", plantTypes[7]),
       new Answer("Это обидно… Но я всё равно поздравлю их.", plantTypes[1]),
       new Answer("Кто-то опять переоценил значение поздравлений.", plantTypes[8])
   ],vars.theme_url + '/images/test/q_8.webp'),

   new Question("Твоя суперсила:",
   [
       new Answer("Молча выживать в самых странных ситуациях.", plantTypes[8]),
       new Answer("Находить гармонию в хаосе и оставаться стильным.", plantTypes[6]),
       new Answer("Впитывать атмосферу, как губка, и адаптироваться моментально.", plantTypes[9]),
       new Answer("Быть неподражаемым и всегда на виду.", plantTypes[5])
   ],vars.theme_url + '/images/test/q_9.webp'),

   new Question("Выбери фразу, которая ближе всего тебе по духу:",
   [
       new Answer("«Не трогай меня — и я никого не трону».", plantTypes[4]),
       new Answer("«Обнимите меня и полейте тёплой водой слов».", plantTypes[1]),
       new Answer("«Я всегда тянусь к солнцу, даже ночью».", plantTypes[2]),
       new Answer("«Я выгляжу как философ, потому что им и являюсь».", plantTypes[3])
   ],vars.theme_url + '/images/test/q_10.webp'),

];

function startTest () {
    test.testInit();
    gen = document.querySelector('input[name="gender"]:checked')?.value; // "f" или "m"
    setTimeout(()=>{
        disclaimerDiv.classList.add('d-none');
        testMainDiv.classList.remove('d-none');
    }, 300)
    console.log(gen);
}

function ajaxGetUpsells(catSlug) {
    console.log(catSlug)
    const data = new URLSearchParams();
    data.append('action', 'get_test_upsells');
    data.append('cat_slug', catSlug);

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
        testUpsellsDiv.innerHTML = result.test_upsells;
    })
    .catch(error => {
        console.error('❌ AJAX error:', error);
    })
    .finally(() => {
        console.debug('⚙️ AJAX complete');
        swiper_product_slider_init();
    });
}

const test = new Test(questions, plantTypes);
const initBtn = document.querySelector('.test__init-btn');
const disclaimerDiv = document.querySelector('.test__disclaimer'); 
const testMainDiv = document.querySelector('.test'); 
const testUpsellsDiv = document.querySelector('.test__result-upsells'); 
let gen;


initBtn.addEventListener ('click', startTest);