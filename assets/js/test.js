//Класс, который представляет сам тест

class Test {
  constructor(questions, plantTypes)
   {
      //Массив с вопросами
      this.questions = questions;
      this.plantTypes = plantTypes;

      this.testWrap = document.querySelector('.test');

      this.questionForm = document.querySelector('.test__answers-form');
 
      //Индекс текущего вопроса
      this.current = 0;

      this.testError = document.querySelector('.test__error');
    
      this.testResult = document.querySelector('.test__result');
      this.testResultName = document.querySelector('.test__result-name');
      this.testResultDescr = document.querySelector('.test__result-descr');
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
      this.resultPageUrl = vars.site_url + '/resultat-testa-' + this.resultPlant.slug;
      console.log(this.resultPageUrl);

      this.testResult.classList.remove('d-none');
      this.testResultName.innerText = `Поздравляем! Вы&nbsp${this.resultPlant.name}!`;
      this.testResultDescr.innerText = this.resultPlant.result;
      this.testShareTelegram.setAttribute('href',`https://telegram.me/share/url?url=${this.resultPageUrl}&text=Посмотри какое я растение`);
      this.testShareWhatsapp.setAttribute('href',`https://web.whatsapp.com/send?text=Посмотри какое я растение - ${this.resultPageUrl}`);
      this.testShareOk.setAttribute('href',`https://connect.ok.ru/offer?url=${this.resultPageUrl}&title=Посмотри какое я растение`);
      this.testShareVk.setAttribute('href',`https://vk.com/share.php?url=${this.resultPageUrl}`);
      this.testResultImage.setAttribute('src',this.resultPlant.image);
      this.testResultImage.setAttribute('alt',this.resultPlant.name);
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
     console.log('answer type is ', this.type.name)
     console.log('answer score is ', this.type.score)
     
     ++this.type.score;
     
     console.log('answer score is ', this.type.score)

   }
}

const plantTypes = [
  {name:'0 Замиокулькас',
    score: 0,
    slug:'zamiokulkas',
    image:'',
    result: 'Если бы вы были комнатным растением, то точно замиокулькасом. Вы бы спокойно стояли в углу офиса, питались комплиментами и двумя каплями воды раз в месяц. Никого не трогали, но выглядели бы так, будто знаете все секреты. Выжили бы в любой обстановке и, возможно, тихо мстили бы за лишние поливы.',
  },
  {name:'1 Спатифиллум',
    score: 0,
    slug:'spatifillum',
    image:'',
    result: 'Мы думаем, что вы - спатифиллум. Вы расцветаете от внимания, грустите без любви и вянете от сквозняков душевных разговоров. Но стоило бы сказать вам комплимент — и вы снова сияли бы, как белый парус надежды. Вы - душа тонкая, ранимая, но чарующая.',
  },
  {name:'2 Хризалидокарпус',
    score: 0,
    slug:'dipsis-areka',
    image:'',
    result: 'Вы - хризалидокарпус. Вы всегда тянетесь к солнцу, любите тепло. Вы — тот, кто приходит в офис в гавайской рубашке и у вас всегда есть солнцезащитные очки. Даже ночью. Вы всегда стремитесь быть «пальмой первой важности» в любой компании и при этом умудряетесь очищать атмосферу — в том числе и моральную. А пыль на листьях считаете личной драмой!',
  },
  {name:'3 Фикус Бенджамина',
    score: 0,
    slug:'fikus-bendzhamina',
    image:'',
    result: '100%, вы - фикус Бенджамина. Вы выглядите шикарно, но скидываете "листья" при малейшем стрессе — опоздали на автобус, кофе не тот, забыли погладить. Любите свет, но не прямой, разговоры — но не громкие. Вы стоите в углу и наблюдаете за всеми с видом зеленого философа.',
  },
  {name:'4 Фикус эластика',
    score: 0,
    slug:'fikus-ehlastika',
    image:'',
    result: 'Уверены, что вы всё-таки фикус эластика. Вас можно считать крепким орешком — стойкий, глянцевый, всегда в хорошей форме. Вам не страшны ни пересуды, ни пересадки. Главное — не трогать вас без надобности, а то выделите "млечный сок" сарказма. И да, пыль с вас лучше сдувать трепетно — с уважением к величию!',
  },
  {name:'5 Антуриум',
    score: 0,
    slug:'anturium',
    image:'https://dev.plantis.shop/wp-content/uploads/2022/09/anturium-dzholi-karma-krasnyj-12-35-2-800x800.webp',
    result: 'Мы думаем, что вы - антуриум. Вы всегда эффектны, ярки и слегка драматичны. Любите внимание, тёплые слова и влажную атмосферу — эмоциональную, конечно! Вы можете обидеться на сквозняки и критику, но быстро приходите в себя, если вас хвалить. Умеете производить впечатление, и даже ваша скука выглядит как премьера модного показа.',
  },
  {name:'6 Аглаонема',
    score: 0,
    slug:'aglaonema',
    image:'',
    result: 'Судя по всему, вы - аглаонема. Вы спокойно переживете любой бардак вокруг — хоть ремонт, хоть драму в коллективе. Неприхотливы, терпеливы, но с изюминкой: всегда с модным пестрым «принтом». Не любите сквозняков и резких слов. Вы за баланс, ухоженность и аромапалочки. Даже в суете вы находите момент для «инстаграмного» кадра.',
  },
  {name:'7 Драцена',
    score: 0,
    slug:'dracena',
    image:'',
    result: 'Драцена - это вы. Вас можно назвать мастером выживания — не требуете слишком много внимания и воды, спокойно переживаете любые стрессовые ситуации. Идеальный стиль - это про вас: стройны и элегантны. Любите перемены, приключения и иногда странные причёски.',
  },
  {name:'8 Сансевиерия',
    score: 0,
    slug:'sansevieriya',
    image:'',
    result: 'Однозначно, вы - сансевиерия. Вы выживаете в любых условиях — хоть в офисе, хоть в пустыне, хоть на семейных сборах. Молчите, как партизан, зато очищаете атмосферу просто фактом своего присутствия. Чрезмерная забота не для вас — не лезьте, я самостоятельный!',
  },
  {name:'9 Эпипремнум',
    score: 0,
    slug:'ehpipremnum',
    image:'',
    result: 'Не удивляйтесь, но вы - эпипремнум. Вы везде умудряетесь найти опору — будь то друзья, работа или даже проблемы. Легко растете в любых условиях, быстро приспосабливаетесь и умело заползаете в самые неожиданные места. Любите уют и тепло, а пыль на себе считаете личным вызовом!',
  }
]

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

const test = new Test(questions, plantTypes);

test.testInit();