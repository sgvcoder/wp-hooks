<?php
echo "<pre>";

require_once __DIR__ . '/../src/Delivery/NovaPoshtaApi2.php';

$np = new NovaPoshtaApi2('bdb5e24ba0488493b10fa48f14f89002');

$np = new NovaPoshtaApi2(
    'bdb5e24ba0488493b10fa48f14f89002',
    'ru', // Язык возвращаемых данных: ru (default) | ua | en
    FALSE, // При ошибке в запросе выбрасывать Exception: FALSE (default) | TRUE
    'file_get_content' // Используемый механизм запроса: curl (defalut) | file_get_content
);

//$result = $np->documentsTracking('59000000000000');

// Получение кода города по названию города и области
//$sender_city = $np->getCity('Белгород-Днестровский', 'Одесская');
//print_r($sender_city);
//$sender_city_ref = $sender_city['data'][0]['Ref'];
//// Получение кода города по названию города и области
//$recipient_city = $np->getCity('Киев', 'Киевская');
//print_r($recipient_city);
//$recipient_city_ref = $recipient_city['data'][0]['Ref'];
//// Дата отправки груза
//$date = date('d.m.Y');
//// Получение ориентировочной даты прибытия груза между складами в разных городах
//$result = $np->getDocumentDeliveryDate($sender_city_ref, $recipient_city_ref, 'WarehouseWarehouse', $date);
//print_r($result);

// Получение кода города по названию города и области
//$sender_city = $np->getCity('Белгород-Днестровский', 'Одесская');
//$sender_city_ref = $sender_city['data'][0]['Ref'];
//// Получение кода города по названию города и области
//$recipient_city = $np->getCity('Киев', 'Киевская');
//$recipient_city_ref = $recipient_city['data'][0]['Ref'];
//// Вес товара
//$weight = 7;
//// Цена в грн
//$price = 5450;
//// Получение стоимости доставки груза с указанным весом и стоимостью между складами в разных городах
//$result = $np->getDocumentPrice($sender_city_ref, $recipient_city_ref, 'WarehouseWarehouse', $weight, $price);
//print_r($result);


// Перед генерированием ЭН необходимо получить данные отправителя
// Получение всех отправителей
$senderInfo = $np->getCounterparties('Sender', 1, '', '');
// Выбор отправителя в конкретном городе (в данном случае - в первом попавшемся)
$sender = $senderInfo['data'][0];
// Информация о складе отправителя
$senderWarehouses = $np->getWarehouses($sender['City']);
// Генерирование новой накладной
$result = $np->newInternetDocument(
    // Данные отправителя
    array(
        // Данные пользователя
        'FirstName' => $sender['FirstName'],
        'MiddleName' => $sender['MiddleName'],
        'LastName' => $sender['LastName'],
        // Вместо FirstName, MiddleName, LastName можно ввести зарегистрированные ФИО отправителя или название фирмы для юрлиц
        // (можно получить, вызвав метод getCounterparties('Sender', 1, '', ''))
        // 'Description' => $sender['Description'],
        // Необязательное поле, в случае отсутствия будет использоваться из данных контакта
        // 'Phone' => '0631112233',
        // Город отправления
        // 'City' => 'Белгород-Днестровский',
        // Область отправления
        // 'Region' => 'Одесская',
        'CitySender' => $sender['City'],
        // Отделение отправления по ID (в данном случае - в первом попавшемся)
        'SenderAddress' => $senderWarehouses['data'][0]['Ref'],
        // Отделение отправления по адресу
        // 'Warehouse' => $senderWarehouses['data'][0]['DescriptionRu'],
    ),
    // Данные получателя
    array(
        'FirstName' => 'Сидор',
        'MiddleName' => 'Сидорович',
        'LastName' => 'Сиродов',
        'Phone' => '0509998877',
        'City' => 'Киев',
        'Region' => 'Киевская',
        'Warehouse' => 'Отделение №3: ул. Калачевская, 13 (Старая Дарница)',
    ),
    array(
        // Дата отправления
        'DateTime' => date('d.m.Y'),
        // Тип доставки, дополнительно - getServiceTypes()
        'ServiceType' => 'WarehouseWarehouse',
        // Тип оплаты, дополнительно - getPaymentForms()
        'PaymentMethod' => 'Cash',
        // Кто оплачивает за доставку
        'PayerType' => 'Recipient',
        // Стоимость груза в грн
        'Cost' => '500',
        // Кол-во мест
        'SeatsAmount' => '1',
        // Описание груза
        'Description' => 'Кастрюля',
        // Тип доставки, дополнительно - getCargoTypes
        'CargoType' => 'Cargo',
        // Вес груза
        'Weight' => '10',
        // Объем груза в куб.м.
        'VolumeGeneral' => '0.5',
        // Обратная доставка
        'BackwardDeliveryData' => array(
            array(
                // Кто оплачивает обратную доставку
                'PayerType' => 'Recipient',
                // Тип доставки
                'CargoType' => 'Money',
                // Значение обратной доставки
                'RedeliveryString' => 4552,
            )
        )
    )
);
print_r($result);
exit;