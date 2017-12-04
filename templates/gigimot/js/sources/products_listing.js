/**
 * Скрипт для страницы списка товаров
 */

(function($){
    'use strict';
    var _document = $(document);
    _document.ready(function(){
        var category = {
            filter : Filter.fromCurrent(),
            watcher : function(t) {
                if(this.timeout_id && this.last_event && t - this.last_event < 1000)
                {
                    clearTimeout(this.timeout_id);
                }
                this.timeout_id = setTimeout(function(){
                    category.resetWatcher();
                    $('.box-filter .price input').eq(0).trigger('change');
                }, 1000);
                this.last_event = t;
            },
            resetWatcher : function() {
                clearTimeout(this.timeout_id);
                delete this.timeout_id;
                delete this.last_event;
            },
            /**
             * Метод, который вызывается перед отправкой запроса
             * @returns {undefined}
             */
            onBeforeFilter: function() {
                var loadMoreProductsButton = $('.load-more-products-block .button');
                if(loadMoreProductsButton.length) {
                    loadMoreProductsButton.addClass('button-disabled').text(dictionary.PRODUCT_LISTING_WAIT);
                }
                $('.category-products-listing-container .products-listing').addClass('loading');
            },
            /**
             * Метод, который вызывается при поступлении ответа от сервера с результатом выбранных фильтров
             * @param {XMLDocument} data
             * @returns {undefined}
             */
            onAfterFilter: function(data) {
                var i = data.documentElement.childNodes.length - 1;
                while(i >= 0)
                {
                    switch(data.documentElement.childNodes[i].nodeName.toLowerCase())
                    {
                        // Обновляем блок с товарами на странице
                        case 'products' :
                            $('.category-products-listing-container').html(data.documentElement.childNodes[i].textContent);
                            break;
                        // Обновляем пагинацию
                        case 'pagination' :
                            var pagination_blocks = $('.block-pagination');
                            if(data.documentElement.childNodes[i].textContent) {
                                if($('.block-pagination').length) {
                                    pagination_blocks.html(data.documentElement.childNodes[i].textContent);
                                } else {
                                    $('.category-listing-params').append('<div class="block-pagination float-right">' + data.documentElement.childNodes[i].textContent + '</div>');
                                    $('.category-products-listing-container').after('<div class="block-pagination common-styled-block align-center">' + data.documentElement.childNodes[i].textContent + '</div>');
                                }
                            } else {
                                pagination_blocks.remove();
                            }
                            break;
                        // Обновляем блок с фильтрами
                        case 'filters' :
                            try {
                                (new Function('$', data.documentElement.childNodes[i].textContent))($);
                            } catch (e) {
                                // pass
                            }
                            setTimeout(category.drawSelectedOptionsBlock, 0)
                            setTimeout(category.sortOptionsValues, 0)
                            break;
                    }
                    i--;
                }
            },
            /* drawSelectedOptionsBlock: function() {
                var checkedOptionsValuesCheckboxes = $('.box-filter input[type="checkbox"]:not([value="all"]):checked'),
                    optionsValuesNames = {},
                    optionsNames = {},
                    optionsIDsToOptionsValuesIDs = {},
                    resultHTML = '',
                    i, checkbox, optionID, optionValueID, optionName, optionValueName;
                // Если нет отмеченных чекбоксов, то удаляем блок с выбранными фильтрами полностью
                if(!checkedOptionsValuesCheckboxes.length) {
                    $('.box-selected-filters').remove();
                    return;
                }
                // Добавлем блок с выбранными фильтрами, если его ещё нет на странице
                if(!$('.box-selected-filters').length) {
                    $('.box-filter').before('\
<div class="box-selected-filters common-styled-block">\n\
    <div class="title">' + dictionary.BOX_HEADING_SELECTED_FILTERS + '</div>\n\
    <div class="clear-selected-filters">' + dictionary.TEXT_CLEAR_SELECTED_FILTERS + '</div>\n\
    <dl></dl>\n\
</div>');
                }
                // Находим ID-шники и названия выбранных значений опций и их групп
                for(var i = 0; i < checkedOptionsValuesCheckboxes.length; i++) {
                    checkbox = checkedOptionsValuesCheckboxes.eq(i);
                    optionValueID = parseInt(checkbox.val());
                    optionID = parseInt(checkbox.attr('name'));
                    optionValueName = checkbox.siblings('label').children('.label').text();
                    optionName = checkbox.parents('.options-group').children('.group-title').text();
                    
                    optionsValuesNames[optionValueID] = optionValueName;
                    optionsNames[optionID] = optionName;
                    if(!optionsIDsToOptionsValuesIDs[optionID]) {
                        optionsIDsToOptionsValuesIDs[optionID] = [];
                    }
                    optionsIDsToOptionsValuesIDs[optionID].push(optionValueID);
                }
                // Формируем результирующую строку HTML
                for(optionID in optionsIDsToOptionsValuesIDs) {
                    resultHTML += '<dt>' + optionsNames[optionID].escapeHtml() + '</dt><dd><ul>';
                    for(i = 0; i < optionsIDsToOptionsValuesIDs[optionID].length; i++) {
                        optionValueID = optionsIDsToOptionsValuesIDs[optionID][i];
                        resultHTML += '<li data-options-id="' + optionID + '" data-options-values-id="' + optionValueID + '">' + optionsValuesNames[optionValueID].escapeHtml() + '<span></span></li>';
                    }
                    resultHTML += '</ul></dd>';
                }
                $('.box-selected-filters dl').html(resultHTML);
            },
            sortOptionsValues: function() {
                $('.box-filter .options').each(function(){
                    var _this = $(this),
                        optionsEnabled = [],
                        optionsDisabled = [];
                    $(this).children('.option').each(function(){
                        var _this = $(this),
                            name  = _this.find('.label').text().toLowerCase(),
                            input = _this.children('input');
                            if(input.val() === 'all') {
                                return;
                            }
                            (input.prop('disabled') ? optionsDisabled : optionsEnabled).push({
                                'name': name,
                                'element': _this
                            });
                    }).promise().done(function(){
                        optionsEnabled.sort(function(a, b){
                            return a.name.localeCompare(b.name);
                        });
                        optionsDisabled.sort(function(a, b){
                            return a.name.localeCompare(b.name);
                        });
                        for(var i = 0; i < optionsEnabled.length; i++) {
                            _this.append(optionsEnabled[i].element);
                        }
                        for(var i = 0; i < optionsDisabled.length; i++) {
                            _this.append(optionsDisabled[i].element);
                        }
                        if(_this.hasClass('ps-container')) {
                            _this.perfectScrollbar('update');
                        }
                    });
                });
            } */
        };
        
        // Отрисовать блок выбранных опций
        // category.drawSelectedOptionsBlock();
        
        // Отсортировать значения опций
        // category.sortOptionsValues();
        
        // Добавить скролл к опциям, у которых есть более 20 значений для выбора
        $('.box-filter .options').each(function(){
            var _this = $(this);
            if(_this.children('.option').length > 20) {
                _this.perfectScrollbar();
            }
        });
        
        // Удалить выбранную опцию при клике на неё в блоке выбранных опций
        /* $('.side-column .column-wrapper').on('click', '.box-selected-filters li', function(){
            $('#filter-' + this.getAttribute('data-options-id') + '-' + this.getAttribute('data-options-values-id')).prop('checked', false).trigger('change');
        }); */
        
        // Удалить все выбранные опции
        /* $('.side-column .column-wrapper').on('click', '.clear-selected-filters', function(){
            $('.box-filter input[type="checkbox"][value="all"]:not(:checked)').prop('checked', true).trigger('change');
        }); */
        
        
        // Ползунок выбора цен в категории и переключение опций фильтров
        var price_min = parseInt($('.box-filter .price .price-min').val()),
            price_max = parseInt($('.box-filter .price .price-max').val());
        $('.box-filter .price .selector').slider({
            range: true,
            min: price_min,
            max: price_max,
            values: [ price_min, price_max ],
            slide: function( e, ui ) {
                $('.box-filter .price .price-min').val(ui.values[ 0 ]);
                $('.box-filter .price .price-max').val(ui.values[ 1 ]);
                category.watcher(e.timeStamp);
            }
        });
        $('.box-filter .price input').on('input', function(e) {
            $('.box-filter .price .selector').slider('values', $(this).hasClass('price-min') ? 0 : 1, this.value);
            category.watcher(e.timeStamp);
        }).on('change', function(){
            category.resetWatcher();
            
            var priceMinInput = $('.box-filter .price .price-min'),
                priceMaxInput = $('.box-filter .price .price-max'),
                priceMin = parseInt(priceMinInput.attr('min')) || 0,
                priceMax = parseInt(priceMaxInput.attr('max')) || 0,
                priceMinInputValue = parseInt(priceMinInput.val()),
                priceMaxInputValue = parseInt(priceMaxInput.val());
            if(priceMinInputValue === priceMin) {
                delete category.filter.price_min;
            } else {
                category.filter.price_min = priceMinInputValue;
            }
            if(priceMaxInputValue === priceMax) {
                delete category.filter.price_max;
            } else {
                category.filter.price_max = priceMaxInputValue;
            }
            
            category.onBeforeFilter();
            category.filter.filter(category.onAfterFilter);
        });
        /* $('.box-filter input').on('change', function() {
            var checkbox, option_id, i;
            category.resetWatcher();
            category.filter.price_min = parseInt();
            category.filter.price_max = parseInt($('.box-filter .price .price-max').val());
            
            var priceMinInput = $('.box-filter .price .price-min'),
                priceMaxInput = $('.box-filter .price .price-max'),
                priceMin = parseInt(priceMinInput.attr('min')) || 0,
                priceMax = parseInt(priceMaxInput.attr('max')) || 0,
                priceMinInputValue = parseInt(priceMinInput.val()),
                priceMaxInputValue = parseInt(priceMaxInput.val());
            if(priceMinInputValue === priceMin) {
                delete category.filter.price_min;
            } else {
                category.filter.price_min = priceMinInputValue;
            }
            if(priceMaxInputValue === priceMax) {
                delete category.filter.price_max;
            } else {
                category.filter.price_max = priceMaxInputValue;
            }
            
            if(this.type === 'checkbox')
            {
                option_id = parseInt(this.name.replace(/\D/g, ''));
                if(option_id)
                {
                    if(this.value === 'all')
                    {
                        this.checked = true;
                        if(category.filter.attributes && option_id in category.filter.attributes)
                        {
                            for(i = 0; i < category.filter.attributes[option_id].length; i++)
                            {
                                checkbox = document.getElementById('filter-' + option_id + '-' + category.filter.attributes[option_id][i]);
                                if(!checkbox)
                                {
                                    continue;
                                }
                                checkbox.checked = false;
                            }
                            delete category.filter.attributes[option_id];
                            category.onBeforeFilter();
                            category.filter.filter(category.onAfterFilter);
                        }
                    }
                    else if(this.checked)
                    {
                        category.filter.addAttribute(option_id, this.value);
                        checkbox = document.getElementById('filter-' + option_id + '-all');
                        if(checkbox)
                        {
                            checkbox.checked = false;
                        }
                    }
                    else
                    {
                        category.filter.removeAttribute(option_id, this.value);
                        checkbox = document.getElementById('filter-' + option_id + '-all');
                        if(checkbox)
                        {
                            checkbox.checked = !(option_id in category.filter.attributes);
                        }
                    }
                }
            }
            category.onBeforeFilter();
            category.filter.filter(category.onAfterFilter);
        }); */
        // Переключение порядка сортировки и количества отображаемых товаров на странице
        _document.on('change', '#sort-order, #display-items', function() {
            category.filter[this.name] = this.value;
            category.onBeforeFilter();
            category.filter.filter(category.onAfterFilter);
            return true;
        });
        // Переключение способа отображения товаров в категории (список/плитка)
        _document.on('click', '.view-switch button', function() {
            $(this).addClass('active').siblings('button').removeClass('active');
            category.filter[this.name] = this.value;
            category.onBeforeFilter();
            category.filter.filter(category.onAfterFilter);
            return false;
        });
        // Подгрузка товаров
        _document.on('click', '.load-more-products-block .button', function(){
            category.filter.row_by_page = parseInt(this.getAttribute('data-row-by-page')) || 12;
            category.onBeforeFilter();
            category.filter.filter(category.onAfterFilter);
            return false;
        });
        // Свернуть/развернуть боковой блок с фильтрами
        $('.side-column .column-wrapper').on('click', '.group-title', function(){
            $(this).parent().toggleClass('collapsed');
        });
    });
})(window.jQuery || window.Zepto)