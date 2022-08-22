<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package gefest
 */

?>


<footer class="footer" data-watch data-watch-once>
    <div class="footer__line line-horizontal">

    </div>
    <div class="footer__wrapper gutter grid_layout">
        <div class="footer__first_line line-vertical_container">
            <div class="line-vertical"></div>
        </div>
        <div class="footer__second_line line-vertical_container">
            <div class="line-vertical"></div>
        </div>


        <div class="footer__logo">
            <a href="" class="footer-logo">
                <svg width="64" height="63" viewBox="0 0 64 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="32.2839" y="53.4531" width="9.54735" height="9.54735" fill="#3A3A1F" />
                    <rect x="21.5958" y="0.00927734" width="9.54735" height="9.54735" fill="#3A3A1F" />
                    <rect x="32.2839" y="42.7549" width="20.4586" height="9.54735" fill="#3A3A1F" />
                    <rect x="10.6842" y="10.6885" width="20.4586" height="9.54735" fill="#3A3A1F" />
                    <rect x="1.14441e-05" y="21.3823" width="31.1426" height="9.54735" fill="#3A3A1F" />
                    <rect x="32.2839" y="32.0659" width="31.1426" height="9.54735" fill="#3A3A1F" />
                    <rect x="32.2839" y="21.3823" width="31.1426" height="9.54735" fill="#3A3A1F" />
                    <rect x="32.2792" width="9.57244" height="22.4841" fill="#3A3A1F" />
                    <rect x="0.00478935" y="32.0566" width="31.1426" height="9.54735" fill="#3A3A1F" />
                    <rect x="21.5936" y="40.293" width="9.57244" height="22.7067" fill="#3A3A1F" />
                </svg>
            </a>
            <form action="#" method="POST" class="form form__footer_logo">
                <div class="form__row">
                    <label for="" class="form__label caption_01">Узнать первым об акциях</label>
                </div>
                <div class="form__row">
                    <input type="text" name="footerFirstName" data-error="Error" placeholder="Представьтесь пожалуйста" class="input input_1">
                </div>
                <div class="form__row">
                    <input type="text" name="footerFirstName" data-error="Error" placeholder="Электронная почта" class="input input_1 input-with-btn">
                </div>

                <button type="submit" class="button button-arrow">
                    <svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.8 0.600098L16.1 4.0001L12.8 7.3001" stroke="#3A3A1F" />
                        <path d="M0 4H16.1" stroke="#3A3A1F" />
                    </svg>
                    <svg width="18" height="8" viewBox="0 0 18 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.8 0.600098L16.1 4.0001L12.8 7.3001" stroke="#3A3A1F" />
                        <path d="M0 4H16.1" stroke="#3A3A1F" />
                    </svg>


                </button>
            </form>
        </div>
        <p class="footer__acknow">
            Договор о сотрудничестве с нами могут заключать как физические, так и юридические лица. Мы всегда готовы пойти на встречу заказчику и стараемся максимально выполнять поставленные требования.
        </p>
        <nav class="footer__socials">
            <span class="caption_01">Мы в соцсетях</span>
            <ul>
                <li>
                    <a href="/" class="link">Instagram</a>
                </li>
                <li>
                    <a href="/" class="link">ВКонтакте</a>
                </li>
                <li>
                    <a href="/" class="link">YouTube</a>
                </li>
            </ul>
        </nav>
        <div class="footer__copyright">
            © ООО «ГЕФЕСТ» 2022
        </div>
        <nav class="footer__products products-footer_1">
            <span class="caption_01">Товары</span>
            <ul>
                <li>
                    <a href="#" class="link">Тротуарная плитка</a>
                </li>
                <li>
                    <a href="#" class="link">Бордюрный камень</a>
                </li>
                <li>
                    <a href="#" class="link">Лотки</a>
                </li>
                <li>
                    <a href="#" class="link">Бордюр оцинкованный</a>
                </li>
            </ul>
        </nav>
        <nav class="footer__products products-footer_2">
            <span class="caption_01">Проекты</span>
            <ul>
                <li>
                    <a href="#" class="link">Частные домовладения</a>
                </li>
                <li>
                    <a href="#" class="link">Коммерческие</a>
                </li>
                <li>
                    <a href="#" class="link">Муниципальные</a>
                </li>
            </ul>
        </nav>
        <div class="footer__contacts">
					<span class="caption_01">
						Контакты
					</span>
            <a href="tel:+79068759597" class="link">+7 906 875 95 97</a>
            <a href="mailto:mail@gefest-plitka.ru" class="link">mail@gefest-plitka.ru</a>
        </div>
        <nav class="footer__resources">
					<span class="caption_01">
						Ресурсы
					</span>
            <ul>
                <li>
                    <a href="#" class="link">Вдохновение</a>
                </li>
                <li>
                    <a href="#" class="link">Документация</a>
                </li>
            </ul>
        </nav>
    </div>
</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>
