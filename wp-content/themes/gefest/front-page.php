<?php
global $post;
get_header(); ?>

<div style="margin-top: 100px;"></div>
    <div class="menu__body-side">
        <ul class="menu__list-side">

<!--            Главное меню в сайдбаре           -->
            <?php
            $args = array(
                'menu'                 => 'menu-main',
                'container'            => false,
                'menu_class'           => 'menu__list-side',
                'echo'                 => true,
                'items_wrap'           => '%3$s',
            );
            wp_nav_menu($args); ?>
            <!--            Подменю Ресурсы           -->
            <ul class="column-one-menu submenu_resources">
                <li class="column-one-menu-heading">Смотреть все <span>30</span></li>
                <li class="side_menu_subtitle feel">Вдохновение <span><?php echo wp_count_posts()->publish;
                ?></span></li>
                <li class="side_menu_subtitle docs">Документы <span><?php echo wp_count_posts()->publish; ?></span></li>


                <!--            Вдохновение           -->
                <?php
                $cat_args=array(
                    'orderby' => 'name',
                    'post_status' => 'publish',
                    'order' => 'ASC',
                    'exclude' => array(5,14,15,16,17,18),
                    'hide_empty' => true
                );
                $categories=get_categories($cat_args);
                $i = 0;
                foreach($categories as $category) {
                    $args=array(
                        'showposts' => 5,
                        'category__in' => array($category->term_id),
                        'caller_get_posts'=>1
                    );
                    $posts=get_posts($args);
                    $category_counter = get_category($category->term_id);
                    $count_posts = $category_counter->category_count;
                    $i++;
                    echo '<ul class="column-two-menu">';
                    if ($i > 0 && $i < 2) {
                        echo '<li class="side_menu_subtitle">Вдохновение <span>' . wp_count_posts()->publish . '</span></li>';
                    }
                    echo '<li class="sub_column_cat">' .
                        '<a href="' . get_category_link( $category->term_id ) . '" 
                            class="column_link">' . $category->name.' <span> ' . $count_posts . ' </span></a> ' .
                        '<ul class="column-three-submenu">';
                            echo '<li class="side_menu_subtitle">' . $category->name.' <span> ' . $count_posts . ' 
                                </span></li>';
                            if ($posts) {
                                foreach($posts as $post) {
                                    setup_postdata($post); ?>
                                    <li class="sub_sub_column_cat">
                                        <a href="<?php the_permalink() ?>" class="sub_column_link">
                                            <?php the_title(); ?> <img src="" alt="" class="sub_sub_column_arrow">
                                        <span class="sub_sub_column_cat_cat">Категория</span>
                                        <span class="sub_sub_column_cat_name"><?php echo $category->name; ?></span>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                    echo '</ul> </li>';
                }
                ?>

                <!--            Документы           -->
                <ul class="column-one-menu submenu_documents">
                    <li class="column-one-menu-heading">Смотреть все <span>30</span></li>
                    <li class="side_menu_subtitle feel">Вдохновение <span><?php echo wp_count_posts()->publish;
                            ?></span></li>
                    <li class="side_menu_subtitle docs">Документы <span><?php echo wp_count_posts()->publish; ?></span></li>
                <?php
                $doc_cat_args=array(
                    'orderby' => 'name',
                    'post_status' => 'publish',
                    'order' => 'ASC',
                    'exclude' => array(5,6,7,8),
                    'hide_empty' => true
                );
                $doc_categories=get_categories($doc_cat_args);
                $doc_i = 0;
                foreach($doc_categories as $doc_category) {
                    $doc_args=array(
                        'showposts' => 5,
                        'category__in' => array($doc_category->term_id),
                        'caller_get_posts'=>1
                    );
                    $doc_posts=get_posts($doc_args);
                    $doc_category_counter = get_category($doc_category->term_id);
                    $doc_count_posts = $doc_category_counter->category_count;
                    $doc_i++;
                    echo '<ul class="column-two-menu">';
                    if ($doc_i > 0 && $doc_i < 2) {
                        echo '<li class="side_menu_subtitle">Вдохновение <span>' . wp_count_posts()->publish . '</span></li>';
                    }
                    echo '<li class="sub_column_cat">' .
                        '<a href="' . get_category_link( $doc_category->term_id ) . '" 
                            class="column_link">' . $doc_category->name.' <span> ' . $doc_count_posts . ' </span></a> ' .
                        '<ul class="column-three-submenu">';
                    echo '<li class="side_menu_subtitle">' . $doc_category->name.' <span> ' . $doc_count_posts . ' 
                                </span></li>';
                    if ($doc_posts) {
                        foreach($doc_posts as $doc_post) {
                            setup_postdata($doc_post); ?>
                            <li class="sub_sub_column_cat">
                                <a href="<?php the_permalink() ?>" class="sub_column_link">
                                    <?php the_title(); ?> <img src="" alt="" class="sub_sub_column_arrow">
                                    <span class="sub_sub_column_cat_cat">Категория</span>
                                    <span class="sub_sub_column_cat_name"><?php echo $doc_category->name; ?></span>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    echo '</ul> </li>';
                }
                ?>
            </ul>
        </ul>
    </div>

<main class="page">
    <section class="hero" data-watch data-watch-once>
        <div class="hero__content">

            <div class="hero__container">
                <div class="hero__wrapper">
                    <h1 class="hero__title" id="heroTitle">
                        продажа материалов
                        для благоустройства территории
                    </h1>
                    <div class="hero__info">
                        Более 500 видов тротуарных плит и элементов
                        благоустройства повышенной прочности.
                    </div>
                    <div class="hero__captions">
                        <span>Официальный дилер завода тротуарной плитки «Выбор»</span>
                        <span>работаем по всей россии</span>
                    </div>
                    <div class="hero__button">
                        <a href="/" class="button-border">
                            ТОВАРЫ
                            <span class="button-border__arrow">
										<svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M12.8 0.599976L16.1 3.99998L12.8 7.29998" stroke="#3A3A1F" />
											<path d="M0 4H16.1" stroke="#3A3A1F" />
										</svg>
									</span>
                        </a>
                    </div>
                    <div class="hero__lines lines-hero">
                        <div class="lines-hero__vertical line-vertical">

                        </div>
                        <div class="lines-hero__vertical line-vertical">

                        </div>
                        <div class="lines-hero__vertical line-vertical">

                        </div>
                        <div class="lines-hero__vertical line-vertical">

                        </div>
                        <div class="lines-hero__vertical line-vertical">

                        </div>
                        <div class="lines-hero__vertical line-vertical">

                        </div>
                    </div>
                </div>
            </div>
            <div class="hero__image">
                <picture>
                    <img src="<?php echo get_template_directory_uri(); ?>/img/hero/image.png" alt="image">
                </picture>

                <div class="hero__image-captions">
							<span>
								АЭРОПОРТ «ПЛАТОВ» <br>
								РОСТОВ НА ДОНУ
							</span>
                    <span>
								Форма <br>
								ЛА–ЛИНИЯ
							</span>
                    <span>
								КОЛЛЕКЦИЯ <br>
								СТОУНМИКС
							</span>
                </div>
            </div>
        </div>
    </section>
    <!-- /.hero -->
    <section class="projects margin-bottom">
        <div class="projects__content">
            <div class="projects__header grid_layout gutter">
                <h2 class="projects__title title">ИЗБРАННЫЕ ПРОЕКТЫ</h2>
                <div class="projects__info">
                    <p>Благодаря особому уровню эстетики, а также высокой степени прочности и гарантированной защиты от потери цвета на долгие года, тротуарная плитка «Выбор» стала широко использоваться для благоустройства территорий социально-значимых объектов: аэропортов, парков, набережных и других мест.</p>
                    <a href="/" class="button-border">
                        СМОТРЕТЬ ВСЕ <span> ПРОЕКТЫ</span>
                        <span class="button-border__arrow">
									<svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M12.8 0.599976L16.1 3.99998L12.8 7.29998" stroke="#3A3A1F" />
										<path d="M0 4H16.1" stroke="#3A3A1F" />
									</svg>
								</span>
                    </a>
                </div>
            </div>
            <div class="projects__list">

                <div class="projects__row row-project grid_layout slider-images-01" data-watch data-watch-once>
                    <div class="line-horizontal projects__line_horizontal_01"></div>
                    <div class="row-project__content">
                        <div class="row-project__title">
                            <span class="caption_01">ПРОЕКТ</span>
                            <a href="#" class="link">Сквер «Вьюжный» Тюмень</a>
                        </div>
                        <div class="row-project__details details-project">
                            <div class="details-project__col">
                                <span class="caption_01">Форма</span>
                                <p>Старый город</p>
                            </div>
                            <div class="details-project__col">
                                <span class="caption_01">Коллекция</span>
                                <p>Стандарт</p>
                            </div>
                            <div class="details-project__col">
                                <dic class="caption_01 slider__pagination slider__pagination--count_image">Изображение 2/4</dic>
                                <div class="slider__navigation">
                                    <button type="button" class="button-border slider-images-01 swiper-button-prev">
                                        <svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.2 0.600098L1.9 4.0001L5.2 7.3001" stroke="#3A3A1F" />
                                            <path d="M18 4H1.9" stroke="#3A3A1F" />
                                        </svg>

                                    </button>
                                    <button type="button" class="button-border slider-images-01 swiper-button-next">
                                        <svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.8 0.600098L16.1 4.0001L12.8 7.3001" stroke="#3A3A1F" />
                                            <path d="M0 4H16.1" stroke="#3A3A1F" />
                                        </svg>

                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="line-vertical line-vertical_2 projects__line_vertical_01"></div>
                    </div>
                    <div class="row-project__images">
                        <div class="container_01">
                            <!-- Оболочка слайдера -->
                            <div class="images__slider swiper">
                                <!-- Двигающееся часть слайдера -->
                                <div class="images__wrapper swiper-wrapper">
                                    <!-- Слайд -->
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/01/01.png" alt="01">
                                    </div>
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/01/02.png" alt="02">
                                    </div>
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/01/01.png" alt="01">
                                    </div>
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/01/02.png" alt="02">
                                    </div>
                                </div>
                                <!-- Если нужна пагинация -->



                            </div>
                        </div>
                    </div>
                </div>

                <div class="projects__row row-project grid_layout slider-images-02" data-watch data-watch-once>
                    <div class="line-horizontal projects__line_horizontal_01"></div>
                    <div class="row-project__content">
                        <div class="row-project__title">
                            <span class="caption_01">ПРОЕКТ</span>
                            <a href="#" class="link">Олимпийская деревня Сочи</a>
                        </div>
                        <div class="row-project__details details-project">
                            <div class="details-project__col">
                                <span class="caption_01">Форма</span>
                                <p>Ла-линия</p>
                            </div>
                            <div class="details-project__col">
                                <span class="caption_01">Коллекция</span>
                                <p>Стандарт</p>
                            </div>
                            <div class="details-project__col">
                                <dic class="caption_01 slider__pagination slider__pagination--count_image">Изображение 2/4</dic>
                                <div class="slider__navigation">
                                    <button type="button" class="button-border slider-images-01 swiper-button-prev">
                                        <svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.2 0.600098L1.9 4.0001L5.2 7.3001" stroke="#3A3A1F" />
                                            <path d="M18 4H1.9" stroke="#3A3A1F" />
                                        </svg>

                                    </button>
                                    <button type="button" class="button-border slider-images-01 swiper-button-next">
                                        <svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.8 0.600098L16.1 4.0001L12.8 7.3001" stroke="#3A3A1F" />
                                            <path d="M0 4H16.1" stroke="#3A3A1F" />
                                        </svg>

                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="line-vertical line-vertical_2 projects__line_vertical_01"></div>
                    </div>
                    <div class="row-project__images">
                        <div class="container_01">
                            <!-- Оболочка слайдера -->
                            <div class="images__slider swiper">
                                <!-- Двигающееся часть слайдера -->
                                <div class="images__wrapper swiper-wrapper">
                                    <!-- Слайд -->
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/02/01.png" alt="01">
                                    </div>
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/02/02.png" alt="02">
                                    </div>
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/02/01.png" alt="01">
                                    </div>
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/02/02.png" alt="02">
                                    </div>
                                </div>
                                <!-- Если нужна пагинация -->



                            </div>
                        </div>
                    </div>
                </div>

                <div class="projects__row row-project grid_layout slider-images-03" data-watch data-watch-once>
                    <div class="line-horizontal projects__line_horizontal_01"></div>
                    <div class="row-project__content">
                        <div class="row-project__title">
                            <span class="caption_01">ПРОЕКТ</span>
                            <a href="#" class="link">Офис Mersedes-Benz Краснодар</a>
                        </div>
                        <div class="row-project__details details-project">
                            <div class="details-project__col">
                                <span class="caption_01">Форма</span>
                                <p>Ла-линия</p>
                            </div>
                            <div class="details-project__col">
                                <span class="caption_01">Коллекция</span>
                                <p>Стандарт</p>
                            </div>
                            <div class="details-project__col">
                                <dic class="caption_01 slider__pagination slider__pagination--count_image">Изображение 2/4</dic>
                                <div class="slider__navigation">
                                    <button type="button" class="button-border slider-images-01 swiper-button-prev">
                                        <svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.2 0.600098L1.9 4.0001L5.2 7.3001" stroke="#3A3A1F" />
                                            <path d="M18 4H1.9" stroke="#3A3A1F" />
                                        </svg>

                                    </button>
                                    <button type="button" class="button-border slider-images-01 swiper-button-next">
                                        <svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.8 0.600098L16.1 4.0001L12.8 7.3001" stroke="#3A3A1F" />
                                            <path d="M0 4H16.1" stroke="#3A3A1F" />
                                        </svg>

                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="line-vertical line-vertical_2 projects__line_vertical_01"></div>
                    </div>
                    <div class="row-project__images">
                        <div class="container_01">
                            <!-- Оболочка слайдера -->
                            <div class="images__slider swiper">
                                <!-- Двигающееся часть слайдера -->
                                <div class="images__wrapper swiper-wrapper">
                                    <!-- Слайд -->
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/03/01.png" alt="01">
                                    </div>
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/03/02.png" alt="02">
                                    </div>
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/03/01.png" alt="01">
                                    </div>
                                    <div class="images__slide swiper-slide">
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/projects/03/02.png" alt="02">
                                    </div>
                                </div>
                                <!-- Если нужна пагинация -->



                            </div>
                        </div>
                    </div>
                </div>


                <div class="line-horizontal line-horizontal_2 projects__line-horizontal_03" data-watch data-watch-once></div>
            </div>

        </div>
    </section>
    <!-- /.projects -->
    <section class="consultation padding-bottom margin-bottom" data-watch data-watch-once>
        <div class="consultation__content grid_layout gutter">
            <h2 class="consultation__title title">
                Консультация <br>
                специалиста
            </h2>
            <div class="consultation__block">
                <p class="consultation__text">
                    Сложный выбор? <br>
                    Наши продавцы-консультанты всегда готовы помочь.
                </p>
                <a href="tel:+79068759597" class="button-border">
                    ЗВОНОК
                    <span> +7 906 875 95 97</span>
                    <span class="button-border__arrow">
								<svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M12.8 0.599976L16.1 3.99998L12.8 7.29998" stroke="#3A3A1F" />
									<path d="M0 4H16.1" stroke="#3A3A1F" />
								</svg>
							</span>
                </a>
                <a href="mailto:mail@gefest-plitka.ru" class="button-border">
                    НАПИСАТЬ
                    <span>mail@gefest-plitka.ru</span>
                    <span class="button-border__arrow">
								<svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M12.8 0.599976L16.1 3.99998L12.8 7.29998" stroke="#3A3A1F" />
									<path d="M0 4H16.1" stroke="#3A3A1F" />
								</svg>
							</span>

                </a>
            </div>
            <div class="consultation__line line-horizontal"></div>
        </div>
    </section>
    <!-- /.consultation -->
    <section class="products" data-watch data-watch-once>
        <div class="products__wrapper">
            <div class="products__header margin-bottom gutter grid_layout">
                <h2 class="products__title title">Товары</h2>
                <div class="products__block">
                    <p class="text_01">
                        Тротуарная плитка «Выбор» насчитывает более 12 форм плит разных размеров и форм. Широкая линейка
                        цветов позволяет подобрать оттенок плитки практически под любую задачу.
                    </p>
                    <a href="#" class="button-border">
                        СМОТРЕТЬ ВСЕ
                        <span>ТОВАРЫ</span>
                        <span class="button-border__arrow">
									<svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M12.8 0.599976L16.1 3.99998L12.8 7.29998" stroke="#3A3A1F" />
										<path d="M0 4H16.1" stroke="#3A3A1F" />
									</svg>
								</span>
                    </a>
                </div>
            </div>
            <div class="products__list gutter grid_layout">
                <a href="#" class="products__item">
                    <div class="products__image">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/products/01.png" alt="01">
                    </div>
                    <h5 class="products__item-title">Антик</h5>
                    <div class="products__field">
                        <div class="products__field-col">
                            <span class="caption_01">Категория</span>
                            <p>Тратуарная плитка</p>
                        </div>
                        <div class="products__field-col">
                            <span class="caption_01">Коллекция</span>
                            <p>Стандарт/Гранит, Листопад, Стоунмикс</p>
                        </div>
                    </div>
                </a>
                <a href="#" class="products__item">
                    <div class="products__image">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/products/02.png" alt="02">
                    </div>
                    <h5 class="products__item-title">Арена</h5>
                    <div class="products__field">
                        <div class="products__field-col">
                            <span class="caption_01">Категория</span>
                            <p>Тратуарная плитка</p>
                        </div>
                        <div class="products__field-col">
                            <span class="caption_01">Коллекция</span>
                            <p>Стандарт/Гранит, Листопад, Стоунмикс</p>
                        </div>
                    </div>
                </a>
                <a href="#" class="products__item">
                    <div class="products__image">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/products/03.png" alt="03">
                    </div>
                    <h5 class="products__item-title">Грандо</h5>
                    <div class="products__field">
                        <div class="products__field-col">
                            <span class="caption_01">Категория</span>
                            <p>Тратуарная плитка</p>
                        </div>
                        <div class="products__field-col">
                            <span class="caption_01">Коллекция</span>
                            <p>Стандарт/Гранит, Листопад, Искусстве...</p>
                        </div>
                    </div>
                </a>
                <a href="#" class="products__item">
                    <div class="products__image">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/products/04.png" alt="04">
                    </div>
                    <h5 class="products__item-title">Паркет</h5>
                    <div class="products__field">
                        <div class="products__field-col">
                            <span class="caption_01">Категория</span>
                            <p>Тратуарная плитка</p>
                        </div>
                        <div class="products__field-col">
                            <span class="caption_01">Коллекция</span>
                            <p>Стандарт/Гранит, Листопад, Стоунмикс</p>
                        </div>
                    </div>
                </a>
                <a href="#" class="products__item">
                    <div class="products__image">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/products/05.png" alt="505">
                    </div>
                    <h5 class="products__item-title">Арена</h5>
                    <div class="products__field">
                        <div class="products__field-col">
                            <span class="caption_01">Категория</span>
                            <p>Тратуарная плитка</p>
                        </div>
                        <div class="products__field-col">
                            <span class="caption_01">Коллекция</span>
                            <p>Стандарт/Гранит, Листопад, Стоунмикс</p>
                        </div>
                    </div>
                </a>
                <a href="#" class="products__item">
                    <div class="products__image">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/products/06.png" alt="06">
                    </div>
                    <h5 class="products__item-title">Грандо</h5>
                    <div class="products__field">
                        <div class="products__field-col">
                            <span class="caption_01">Категория</span>
                            <p>Тратуарная плитка</p>
                        </div>
                        <div class="products__field-col">
                            <span class="caption_01">Коллекция</span>
                            <p>Стандарт/Гранит, Листопад, Искусстве...</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="products__line line-horizontal"></div>
        </div>
    </section>
    <!-- /.products -->
    <section class="posts margin-bottom" data-watch data-watch-once>
        <div class="posts__wrapper">
            <div class="posts__header gutter grid_layout">
                <h2 class="posts__title title">
                    Вдохновение <br> живет здесь
                </h2>
                <div class="posts__block">
                    <p class="text_01">
                        Идеи и размышления для ваших проектов.
                    </p>
                    <a href="#" class="button-border">
                        СМОТРЕТЬ ВСЕ
                        <span>СТАТЬИ</span>
                        <span class="button-border__arrow">
									<svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M12.8 0.599976L16.1 3.99998L12.8 7.29998" stroke="#3A3A1F" />
										<path d="M0 4H16.1" stroke="#3A3A1F" />
									</svg>
								</span>
                    </a>
                </div>

            </div>
            <div class="posts__list">
                <a href="#" class="posts__article" data-watch-once data-watch>
                    <article class="posts__item item-post gutter grid_layout">
                        <div class="item-post__image">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/posts/01.png" alt="01">
                        </div>
                        <div class="item-post__content">
                            <div class="item-post__title">Топ 7 моделей тротуарной плитки в Тюмени</div>
                            <p class="item-post__summary text_01">
                                Чтобы выбрать максимально подходящую вашим запросам плитку, следует познакомиться с наиболее распространенными моделями.
                            </p>
                            <span class="item-post__caption item-post__caption_category caption_01">Тротуарная плитка</span>
                            <span class="item-post__caption item-post__caption_date caption_01">29 июня 2022</span>
                        </div>
                    </article>
                    <div class="posts__line line-horizontal item-post_line_1"></div>
                </a>

                <a href="#" class="posts__article" data-watch-once data-watch>
                    <article class="posts__item item-post gutter grid_layout">
                        <div class="item-post__image">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/posts/02.png" alt="02">
                        </div>
                        <div class="item-post__content">
                            <div class="item-post__title">Применение тротуарной плитки в ландшафтном дизайнеи</div>
                            <p class="item-post__summary text_01">
                                В ландшафтном дизайне тротуарная плитка уже неотъемлемый атрибут. Усадьбы, коттеджные поселки, площадки с мощеными плиткой участками преображаются практически мгновенно, поскольку монтажные работы проводятся достаточно быстро.
                            </p>
                            <span class="item-post__caption item-post__caption_category caption_01">Тротуарная плитка</span>
                            <span class="item-post__caption item-post__caption_date caption_01">29 июня 2022</span>
                        </div>
                    </article>
                    <div class="posts__line line-horizontal item-post_line_1"></div>
                </a>

                <a href="#" class="posts__article" data-watch-once data-watch>
                    <article class="posts__item item-post gutter grid_layout">
                        <div class="item-post__image">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/posts/03.png" alt="03">
                        </div>
                        <div class="item-post__content">
                            <div class="item-post__title">Топ 7 моделей тротуарной плитки в Тюмени</div>
                            <p class="item-post__summary text_01">
                                Чтобы выбрать максимально подходящую вашим запросам плитку, следует познакомиться с наиболее распространенными моделями.
                            </p>
                            <span class="item-post__caption item-post__caption_category caption_01">Тротуарная плитка</span>
                            <span class="item-post__caption item-post__caption_date caption_01">29 июня 2022</span>
                        </div>
                    </article>
                    <div class="posts__line line-horizontal item-post_line_1"></div>
                </a>
            </div>
        </div>
    </section>
    <!-- /.posts -->
</main>

<?php get_footer(); ?>