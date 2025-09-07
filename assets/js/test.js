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
            console.log(this.progressBar)
            console.log(this.current)
          this.questions[this.current].renderQuestion();
          this.progressBar.setAttribute('value',this.current+1);
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
        testNavWrap.classList.remove('d-none');

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

        const wa = new URL('https://wa.me/?text=');
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
        this.testResultImage.setAttribute('src',this.resultPlant.image[gen]);
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
  new Question("Интересный факт: в Японии есть термин инемури — короткий сон прямо на рабочем месте, который считается признаком усердия, а не лени. Короткий отдых во время рабочего дня - почему бы и нет? А каким будет твой идеальный рабочий день?",
   [
       new Answer("Всё по плану, без лишней нервотрёпки, с ароматом свежесваренного кофе и полным дзеном.", plantTypes[6]),
       new Answer("Периоды активной работы сменяются моментами созерцания.", plantTypes[3]),
       new Answer("Много общения, увлекательных дискуссий, комплиментов и немного, для разнообразия, работы.", plantTypes[5]),
       new Answer("Главное, чтобы никто не мешал. Я сам прекрасно знаю, что и как нужно сделать.", plantTypes[8])
   ],vars.theme_url + '/images/test/q_6.webp'),
  new Question("На школьном балу в фильме «Назад в будущее» Марти МакФлай так зажёг на гитаре, что удивил даже 1955 год. А как ты обычно ведёшь себя на вечеринке?",
    [
        new Answer("Люблю быть в центре внимания - эффектно появиться, ослепить всех своим нарядом и стать предметом всеобщего обсуждения.", plantTypes[5]),
        new Answer("Я сижу в уголке, наблюдаю за происходящим, подслушиваю секреты и делаю правильные выводы.", plantTypes[8]),
        new Answer(["Завожу душные душевные разговоры и внимательно слушаю каждого, кто готов поделиться своими переживаниями."], plantTypes[1]),
        new Answer("Я там, где весело. Но если мне скучно, то я всегда готов незаметно улизнуть на другую вечеринку.", plantTypes[7])
    ],
    vars.theme_url + '/images/test/q_2.webp',
  ),
  new Question("Мудрые цитаты бывают не только в книгах. Ими наполнены сторис бывшего, а также речь таксиста в три часа ночи. А какая фраза ближе тебе по духу?",
   [
       new Answer("«Меньше слов, больше фотосинтеза дела».", plantTypes[4]),
       new Answer("«Семь раз отмерь, один раз полей».", plantTypes[1]),
       new Answer("«Не руби пальму, под которой отдыхаешь».", plantTypes[2]),
       new Answer("«Я стоял и молча наблюдал за вами, поэтому «Я знаю, что вы сделали прошлым летом».", plantTypes[3])
   ],vars.theme_url + '/images/test/q_10.webp'
  ),

  new Question("Когда Данте был изгнан из горячо любимой Флоренции под страхом смерти за критику власти, он написал “Божественную комедию”, где в красках расписал ужасные мучения всех его врагов и критиков в аду. А как ты реагируешь на критику?",
   [
       new Answer("Саркастично улыбаюсь и делаю вид, что мне всё равно. Но автора критики я запомнил.", plantTypes[4]),
       new Answer("Очень переживаю, но в конечном итоге благодарю – ведь это помогает стать лучше.", plantTypes[1]),
       new Answer("Нормально воспринимаю! Только произносите её тихо и, пожалуйста, не сегодня.", plantTypes[3]),
       new Answer("Критика — просто шум. Я выше этого.", plantTypes[0])
   ],
    vars.theme_url + '/images/test/q_3.webp'
  ),

  new Question("Главные герои комедии «Евротур» и не предполагали, что в Братиславе можно так отдохнуть всего на 2$. Один известный официант получил от них чаевые в размере пяти центов и решил открыть собственный отель, а не уйти на заслуженный покой. А если бы у тебя представилась такая возможность, какой вид отдыха выбрал бы ты?",
    [
        new Answer("Остаться дома, укутаться в тёплый плед, смотреть любимые сериалы и заказать еду на дом – вот он, идеальный баланс.", plantTypes[9]),
        new Answer("Уехать на пляж, надеть солнечные очки и забыть обо всём на свете.", plantTypes[2]),
        new Answer("Займусь чем-нибудь для души. Спонтанное приключение? Или может, начать ремонт?!", plantTypes[6]),
        new Answer("Провести день в одиночестве, читая философские трактаты с чашкой ароматного кофе.", plantTypes[3])
    ],
    vars.theme_url + '/images/test/q_1.webp'
  ),

  new Question("Жизненный путь Оскара Уайльда — это путь эстета. Уайльда - отец эстетизма, он призывал поклоняться красоте, это было его жизненным кредо. Он одевался так, что многие его скандальные образы оставили свой след в эпохе и истории моды. Выбрать сдержанную элегантность или устроить дерзкий перформанс в стиле Оскара Уайльда — какой вариант ближе тебе?",
   [
       new Answer("Строгость и лаконичность, но с запоминающейся деталью.", plantTypes[7]),
       new Answer("Цветные узоры, необычные аксессуары и немного оверсайза.", plantTypes[6]),
       new Answer("Броско и экстравагантно. Мы же на Китай-Городе, в конце концов.", plantTypes[5]),
       new Answer("Нестареющая классика: монохром, минимализм, аккуратность.", plantTypes[4])
   ],
    vars.theme_url + '/images/test/q_4.webp'
  ),

  new Question("Древнегреческому философу Гераклиту из Эфеса приписывают авторство изречения «всё течёт, всё меняется». С тех пор прошло уже более двух тысяч лет, и много воды утекло. А как ты относишься к переменам?",
   [
       new Answer("Зачем бояться перемен, если можно сделать всё на своё усмотрение.", plantTypes[0]),
       new Answer("Люблю перемены, особенно если они влекут за собой новые горизонты и возможности для роста!", plantTypes[7]),
       new Answer("Немного напрягаюсь, но быстро адаптируюсь к новым условиям.", plantTypes[9]),
       new Answer("Перемены? Сначала придётся сбросить пару листьев от волнения.", plantTypes[3])
   ],vars.theme_url + '/images/test/q_5.webp'
  ),

  new Question("Набоков увлекался ловлей и коллекционированием бабочек, Куприн грезил воздухоплаванием, Гоголь с удовольствием занимался вязанием и шитьем, а Бродский предпочитал и вовсе не выходить из комнаты. А как ты проводишь свое свободное время?",
   [
       new Answer("Составляю планы на неделю, на месяц, на год и на случай апокалипсиса.", plantTypes[4]),
       new Answer("Нежусь, медитирую или просто бездельничаю. Желательно в тёплом углу или под одеялом.", plantTypes[0]),
       new Answer("Встречаюсь с друзьями, ведь для хорошей вечеринки повод не нужен. ", plantTypes[2]),
       new Answer("Украшаю дом, создаю уют, делаю что-то красивое.", plantTypes[9])
   ],vars.theme_url + '/images/test/q_7.webp'
  ),

  new Question("Поговаривают, самого Юлия Цезаря однажды забыли поздравить с Сатурналиями. Он, конечно, не обиделся — просто стал диктатором. А если тебя забудут поздравить с праздником, как отреагируешь?",
   [
       new Answer("Сделаю вид, что не обиделся, но потом обязательно устрою драму по этому поводу.", plantTypes[5]),
       new Answer("Это не мои проблемы. Отмечу праздник с людьми, у которых всё в порядке с памятью.", plantTypes[7]),
       new Answer("Это обидно… Но я всё равно поздравлю их, когда придёт время.", plantTypes[1]),
       new Answer("Кому нужны эти поздравления? Они, как всегда, переоценены.", plantTypes[8])
   ],vars.theme_url + '/images/test/q_8.webp'
  ),

  new Question("Суперсилой некоторых кинокомпаний стало умение зарабатывать миллиарды долларов на фильмах про парней в облегающих трико с разными способностями. А какая есть суперсила у тебя?",
   [
       new Answer("Выходить сухим из воды в самых немыслимых ситуациях.", plantTypes[8]),
       new Answer("Находить гармонию в хаосе и всегда оставаться иконой стиля.", plantTypes[6]),
       new Answer("Впитывать атмосферу, как губка, и моментально адаптироваться.", plantTypes[9]),
       new Answer("Быть неподражаемым и всегда находиться в центре внимания.", plantTypes[5])
   ],vars.theme_url + '/images/test/q_9.webp'
  ),

   

];

function initTest () {
    test.testInit();
    introDiv.classList.add('d-none');
    genDiv.classList.remove('d-none');
    genBtn.addEventListener('click',startTest);
}

function startTest () {
    genDiv.classList.add('d-none');
    gen = document.querySelector('input[name="gender"]:checked')?.value; // "f" или "m"
    // setTimeout(()=>{
        testTitle.classList.add('d-none');
        testNavWrap.classList.add('d-none');
        testMainDiv.classList.remove('d-none');
        window.scrollTo(0, 0);
    // }, 300)
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
const introDiv = document.querySelector('.test__intro'); 
const genDiv = document.querySelector('.test__select-gen');
const genBtn = document.querySelector('.test__select-gen-btn');
const testTitle = document.querySelector('.test-page .entry-title'); 
const testNavWrap = document.querySelector('.header__nav-wrap'); 
const testMainDiv = document.querySelector('.test'); 
const testUpsellsDiv = document.querySelector('.test__result-upsells'); 
let gen;


initBtn.addEventListener ('click', initTest);