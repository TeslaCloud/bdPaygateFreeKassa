# [bd]Paygate: WalletOne
Данная модификация позволяет вам настроить приём платежей на своем форуме через платежный шлюз [Free-Kassa](https://www.free-kassa.ru/)

## Требования
Требуется аддон [bd] Paygates версии не ниже 1.5.2

## Установка и настройка

### Настройка магазина FreeKassa

* Откройте Настройки вашего магазина
 * Укажите URL оповещения в формате 'http://domain.com/bdpaygate/callback.php?p=freekassa'. Тип запроса должен быть "POST".
 * Так же не забудьте указать 'URL возврата'. Укажите там ссылку на главную страницу вашего форума и выберите метод 'GET'.
* Все должно быть примерно так:
![Image](https://matew.pw/screens/clip-2016-08-12-22-53-03-62039290.png)

### Настройка XenForo

* Укажите в настройках данные, которые вы получили на странице своего магазина. Все поля являются обязательными.
![Image](https://matew.pw/screens/clip-2016-08-12-22-54-41-67729737.png)
