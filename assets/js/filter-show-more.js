const filterValuesDefault = new Set([
  'd22',
  'd15',
  'd18',
  'd26',
  'd30',
  'd34',
  'd37',
  '20x20',
  '30x30',
]);

const diametrFilter = document.querySelector('.filter_diametr_gorshka');
const diametrFilterWrapHeightMax = 354;

if (diametrFilter) {
  const diametrFilterWrap = diametrFilter.querySelector('.bapf_body');
  const diametrFilterItems = Array.from(diametrFilter.querySelectorAll('li'));

  if (diametrFilterWrap && diametrFilterItems.length > filterValuesDefault.size) {
    const showMoreBtn = document.createElement('button');

    showMoreBtn.type = 'button';
    showMoreBtn.classList.add('filter-show-more-btn');
    diametrFilter.appendChild(showMoreBtn);

    document.documentElement.style.setProperty(
      '--diametrFilterWrapHeightMax',
      `${diametrFilterWrapHeightMax}px`
    );

    let isExpanded = false;

    const updateFilter = () => {
      diametrFilterItems.forEach((item) => {
        const input = item.querySelector('input');
        const isDefault = input && filterValuesDefault.has(input.value);

        item.classList.toggle('d-none', !isExpanded && !isDefault);
      });

      diametrFilterWrap.classList.toggle('hidden', !isExpanded);

      requestAnimationFrame(() => {
        diametrFilterWrap.classList.toggle(
          'scroll',
          isExpanded && diametrFilterWrap.scrollHeight > diametrFilterWrapHeightMax
        );

        document.documentElement.style.setProperty(
          '--diametrFilterWrapHeight',
          `${diametrFilterWrap.scrollHeight}px`
        );
      });

      showMoreBtn.textContent = isExpanded ? 'Свернуть' : 'Показать все';
    };

    showMoreBtn.addEventListener('click', () => {
      isExpanded = !isExpanded;
      updateFilter();

      if (!isExpanded) {
        document
          .querySelector('.catalog__sidebar-filters')
          ?.scrollIntoView({ behavior: 'smooth' });
      }
    });

    updateFilter();
  }
}


//search field for plants names filter
function setSearchFilterField() {
    const filterHeader = document.querySelector('.filter_plant_name .bapf_head');
    const filterBody = document.querySelector('.filter_plant_name .bapf_body');
    const searchInput = document.querySelector('.berocket-search-input');
    const checkboxes = document.querySelectorAll('.filter_plant_name li');

    if (searchInput && checkboxes.length) {
        searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        checkboxes.forEach((li) => {
            const label = li.textContent.toLowerCase();
            li.style.display = label.includes(query) ? '' : 'none';
        });
        });
    }

    if (filterBody && filterHeader) {      
      // Функция для проверки и скрытия/показа
      const checkDisplayState = () => {
          const computedStyle = window.getComputedStyle(filterBody);
          if (computedStyle.display === 'none') {
              searchInput.classList.add('d-none');
          } else {
              searchInput.classList.remove('d-none');
          }
      };
      
      // Проверяем сразу при загрузке
      checkDisplayState();
      
      // Наблюдаем за изменениями атрибута style
      const observer = new MutationObserver(checkDisplayState);
      observer.observe(filterBody, {
          attributes: true,
          attributeFilter: ['style']
      });
      
      filterHeader.addEventListener('click', function() {
          searchInput.classList.toggle('d-none');
      });
  }
}

document.addEventListener('DOMContentLoaded', setSearchFilterField);