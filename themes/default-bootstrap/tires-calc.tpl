<div class="tires-calc">
    <h3 class="tires-calc__title"><span
                class="tires-calc__title-text">{l s='Подбор шин по параметрам' mod='tirecalc'}</span></h3>
    <div class="tires-calc__row clearfix">
        <div class="container">
            <div class="row">
                <div class="tires-calc__col tires-calc-col">
                    <div class="tires-calc-col__top">
                        <p class="tires-calc-col__top-text">{l s='Грузовые перевозки' mod='tirecalc'}</p>
                        <i class="icon truck-light-icon"></i></div>
                    <div class="tires-calc-col__header">
                        <p>{l s='Применимость' mod='tirecalc'}</p>
                    </div>
                    <ul class="tires-calc-col__list">
                        <li class="tires-calc-col__item">
                            <input type="radio" name="applicability" id="applicability_1" class="not_uniform tires-calc-col__input tires-calc-col__input_applicability_1">
                            <label for="applicability_1"
                                   class="tires-calc-col__label"> <span class="tires-calc-col__icon"><i
                                            class="icon applicability_1"></i></span> {l s='Автомагистраль. Оптимально для автомагистралей.' mod='tirecalc'}</label>
                        </li>
                        <li class="tires-calc-col__item">
                            <input type="radio" name="applicability" id="applicability_2" class="not_uniform tires-calc-col__input tires-calc-col__input_applicability_2">
                            <label for="applicability_2"
                                   class="tires-calc-col__label"> <span class="tires-calc-col__icon"><i
                                            class="icon applicability_2"></i></span> {l s='Региональный. Для магистральных и региональных перевозок.' mod='tirecalc'}
                                </label>
                        </li>
                        <li class="tires-calc-col__item">
                            <input type="radio" name="applicability" id="applicability_3" class="not_uniform tires-calc-col__input tires-calc-col__input_applicability_3">
                            <label for="applicability_3"
                                   class="tires-calc-col__label"> <span class="tires-calc-col__icon"><i
                                            class="icon applicability_3"></i></span> {l s='Дорога/Бездорожье. Для смешанных условий.' mod='tirecalc'}</label>
                        </li>
                    </ul>
                </div>
                <div class="tires-calc__col tires-calc-col">
                    <div class="tires-calc-col__top">
                        <p class="tires-calc-col__top-text">{l s='Пассажирские перевозки' mod='tirecalc'}</p>
                        <i class="icon car-light-icon"></i></div>
                    <div class="tires-calc-col__header">
                        <p>{l s='Характеристики' mod='tirecalc'}</p>
                    </div>
                    <ul class="tires-calc-col__list">
                        <li class="tires-calc-col__item">
                            <input type="radio" name="characteristics" id="characteristics_1" class="not_uniform tires-calc-col__input tires-calc-col__input_characteristics_1">
                            <label for="characteristics_1"
                                   class="tires-calc-col__label"> <span class="tires-calc-col__icon"><i
                                            class="icon characteristics_1"></i></span> {l s='Зима.' mod='tirecalc'}</label>
                        </li>
                        <li class="tires-calc-col__item">
                            <input type="radio" name="characteristics" id="characteristics_2" class="not_uniform tires-calc-col__input tires-calc-col__input_characteristics_2">
                            <label for="characteristics_2"
                                   class="tires-calc-col__label"> <span class="tires-calc-col__icon"><i
                                            class="icon characteristics_2"></i></span> {l s='Восстановленные шины.' mod='tirecalc'}</label>
                        </li>
                    </ul>
                </div>
                <div class="tires-calc__col tires-calc-col">
                    <div class="tires-calc-col__top">
                        <p class="tires-calc-col__top-text">{l s='Строительство' mod='tirecalc'}</p>
                        <i class="icon big_truck-light-icon"></i></div>
                    <div class="tires-calc-col__header">
                        <p>{l s='Ось' mod='tirecalc'}</p>
                    </div>
                    <ul class="tires-calc-col__list">
                        <li class="tires-calc-col__item">
                            <input type="radio" name="axis" id="axis_1" class="not_uniform tires-calc-col__input tires-calc-col__input_axis_1">
                            <label for="axis_1"
                                   class="tires-calc-col__label"> <span class="tires-calc-col__icon"><i
                                            class="icon axis_1"></i></span> {l s='Рулевая ось.' mod='tirecalc'}</label>
                        </li>
                        <li class="tires-calc-col__item">
                            <input type="radio" name="axis" id="axis_2" class="not_uniform tires-calc-col__input tires-calc-col__input_axis_2">
                            <label for="axis_2"
                                   class="tires-calc-col__label"> <span class="tires-calc-col__icon"><i
                                            class="icon axis_2"></i></span> {l s='Ведущая ось.' mod='tirecalc'}</label>
                        </li>
                        <li class="tires-calc-col__item">
                            <input type="radio" name="axis" id="axis_3" class="not_uniform tires-calc-col__input tires-calc-col__input_axis_3">
                            <label for="axis_3"
                                   class="tires-calc-col__label"> <span class="tires-calc-col__icon"><i
                                            class="icon axis_3"></i></span> {l s='Прицепная ось.' mod='tirecalc'}</label>
                        </li>
                    </ul>
                </div>
                <div class="tires-calc__col tires-calc-col tires-calc__col_last">
                    <div class="tires-calc-col__top tires-calc-col__top_transparent"></div>
                    <div class="tires-calc-col__header">
                        <p>{l s='Выбрать параметр' mod='tirecalc'}</p>
                    </div>
                    <ul class="tires-calc-col__list">
                        <li class="tires-calc-col__item">
                            <select name="width" id="width" class="tires-calc-col__select form-control">
                                <option value="1">{l s='Ширина' mod='tirecalc'}</option>
                                <option value="2">{l s='Ширина' mod='tirecalc'}</option>
                            </select>
                        </li>
                        <li class="tires-calc-col__item">
                            <select name="profile" id="profile" class="tires-calc-col__select form-control">
                                <option value="1">{l s='Профиль' mod='tirecalc'}</option>
                                <option value="2">{l s='Профиль' mod='tirecalc'}</option>
                            </select>
                        </li>
                        <li class="tires-calc-col__item">
                            <select name="diameter" id="diameter" class="tires-calc-col__select form-control">
                                <option value="1">{l s='Диаметр' mod='tirecalc'}</option>
                                <option value="2">{l s='Диаметр' mod='tirecalc'}</option>
                            </select>
                        </li>
                        <li class="tires-calc-col__item">
                            <select name="manufacturer" id="manufacturer" class="tires-calc-col__select form-control">
                                <option value="1">{l s='Производитель' mod='tirecalc'}</option>
                                <option value="2">{l s='Производитель' mod='tirecalc'}</option>
                            </select>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>