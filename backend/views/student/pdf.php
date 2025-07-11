<?php

use common\models\Student;
use common\models\Direction;
use common\models\Exam;
use common\models\StudentPerevot;
use common\models\StudentDtm;
use common\models\Course;
use Da\QrCode\QrCode;
use frontend\models\Contract;
use common\models\User;
use common\models\Consulting;
use common\models\StudentMaster;
use common\models\Branch;

/** @var Student $student */
/** @var Direction $direction */
/** @var User $user */

function   ikYearUz($number)
{
    $years = floor($number);

    $months = round(($number - $years) * 12);

    if ($months == 12) {
        $years++;
        $months = 0;
    }

    return "$years yil $months oy";
}
function   ikYearRu($number)
{
    $years = floor($number);

    $months = round(($number - $years) * 12);

    if ($months == 12) {
        $years++;
        $months = 0;
    }

    return "$years года и $months месяцев";
}
$user = $student->user;
$cons = Consulting::findOne($user->cons_id);
$eduDirection = $student->eduDirection;
$direction = $eduDirection->direction;
$full_name = $student->last_name . ' ' . $student->first_name . ' ' . $student->middle_name;
$code = '';
$joy = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$date = '';
$link = '';
$con2 = '';
if ($student->edu_type_id == 1) {
    $contract = Exam::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'status' => 3,
        'is_deleted' => 0
    ]);
    $code = 'Q3/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i", $contract->confirm_date);
    $link = '1&id=' . $contract->id;
    $con2 = '3' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 2) {
    $contract = StudentPerevot::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'P3/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i", $contract->confirm_date);
    $link = '2&id=' . $contract->id;
    $con2 = '3' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 3) {
    $contract = StudentDtm::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'D3/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i:s", $contract->confirm_date);
    $link = '3&id=' . $contract->id;
    $con2 = '3' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 4) {
    $contract = StudentMaster::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'M3/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i:s", $contract->confirm_date);
    $link = '4&id=' . $contract->id;
    $con2 = '3' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
}

$student->is_down = 1;
$student->update(false);

$filial = Branch::findOne($student->branch_id);

$trType = $direction->type;

$number = '';
$mfo = '';
$bankUz = '';
$bankRu = '';
$inn = '';
if ($cons->hr != null) {
    $hrs = json_decode($cons->hr, true);
    foreach ($hrs as $key => $hr) {
        if ($key == $trType) {
            $number = $hr['number'] ?? null;
            $mfo = $hr['mfo'] ?? null;
            $bankUz = $hr['bankUz'] ?? null;
            $bankRu = $hr['bankRu'] ?? null;
            $inn = $hr['inn'] ?? null;
            break;
        }
    }
}


$qr = (new QrCode('https://qabul.sarbon.university/site/contract?key=' . $link . '&type=3'))
    ->setSize(120, 120)
    ->setMargin(10)
    ->setForegroundColor(1, 89, 101);
$img = $qr->writeDataUri();

$lqr = (new QrCode('https://license.gov.uz/registry/abd0350c-93de-4723-a71f-f0513b945c19'))->setSize(100, 100)
    ->setMargin(10)
    ->setForegroundColor(1, 89, 101);
$limg = $lqr->writeDataUri();

?>

<table width="100%" style="font-family: 'Times New Roman'; font-size: 13px; border-collapse: collapse;" cellpadding="8px">
    <tr>
        <td colspan="4">
            <table width="100%" style="font-family: 'Times New Roman'; border-bottom: 2px solid #000000; padding-bottom: 5px; font-size: 14px; border-collapse: collapse;">
                <tr>
                    <td colspan="2">
                        <b>SARBON UNIVERSITETI</b>
                    </td>
                    <td colspan="2" style="font-style: italic; font-size: 11px; text-align: right;">
                        <?= $date ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: center; border: 1px solid #000;">
            <b>
                To‘lov-kontrakt (uch tomonlama) asosida mutaxassis tayyorlashga <br>
                <?= $code ?>/<?= str_replace('.', '', $direction->code) ?>/<?= $contract->id ?> - sonli SHARTNOMA
            </b>
        </td>
        <td colspan="2" style="text-align: center; border: 1px solid #000;">
            <b>
                Договор № <?= $code ?>/<?= str_replace('.', '', $direction->code) ?>/<?= $contract->id ?>
                (трехсторонний ) на подготовку специалиста на основе оплаты
            </b>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="border: 1px solid #000;">
            <?= $date ?>
        </td>
        <td colspan="2" style="text-align: right; border: 1px solid #000;">
            Toshkent shahri
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            Vazirlar Mahkamasining “Oliy ta’lim muassasalariga o‘qishga qabul qilish, talabalar o‘qishini
            ko‘chirish, qayta tiklash va o‘qishdan chetlashtirish tartibi to‘g‘risidagi nizomlarni
            tasdiqlash haqida” 2017-yil 20-iyundagi 393-son qarori, O‘zbekiston Respublikasi oliy va o‘rta maxsus
            ta’lim vazirining 2012-yil 28-dekabrdagi 508-son buyrug‘i (ro‘yxat raqami 2431, 2013-yil 26-fevral) bilan
            tasdiqlangan Oliy va o‘rta maxsus, kasb-hunar ta’limi muassasalarida o‘qitishning to‘lov-shartnoma shakli
            va undan tushgan mablag‘larni taqsimlash tartibi to‘g‘risidagi Nizomga muvofiq, <b>SARBON UNIVERSITETI</b>
            oliy ta’lim tashkiloti (keyingi o‘rinlarda “Ta’lim muassasasi”) nomidan, Ustav asosida ish yurituvchi
            direktor Sharipov Muzaffar Tolibdjonovich, ikkinchi tomondan – (keyingi o‘rinlarda “Buyurtmachi”)
            nomidan ________________________________ asosida ish yurituvchi _________________________________________ yoki
            fuqaro ____________________________________,
            uchinchi tomondan – <b><?= $full_name ?></b> (keyingi o‘rinlarda “Ta’lim oluvchi”)
            (keyingi o‘rinlarda birgalikda “Tomonlar” deb yuritiladi),
            o‘rtasida mazkur shartnoma quyidagilar haqida tuzildi:
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            Во исполнение Постановления Кабинета Министров Республики Узбекистан №393 от 20 июня 2017 года
            «Об утверждении положений о порядке приема в высшие образовательные учреждения, перевода, восстановления
            и отчисления студентов», а также Приказа министра высшего и среднего специального образования
            Республики Узбекистан от 28 декабря 2012 года №508 (рег. №2431 от 26 февраля 2013 года), утвердившего
            «Положение о порядке организации обучения в высших, средних специальных и профессиональных
            образовательных учреждениях на платно-контрактной основе и распределении поступающих от этого средств»,
            с одной стороны — высшее образовательное учреждение SARBON UNIVERSITY, в лице директора
            Шарипова Музаффара Толибджоновича, действующего на основании Устава (в дальнейшем — «Образовательное учреждение»),
            с другой стороны — в лице ___________________________________________, действующего на
            основании ____________________________ или гражданин __________________________________ (в дальнейшем — «Заказчик»),
            и с третьей стороны — <b><?= $full_name ?></b> (в дальнейшем — «Обучающийся»),
            совместно именуемые «Стороны», заключили настоящий договор о нижеследующем:
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>I. SHARTNOMA PREDMETI</b>
        </td>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>I. ПРЕДМЕТ ДОГОВОРА</b>
        </td>
    </tr>



    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            1.1. Ta’lim muassasasi ta’lim xizmatini ko‘rsatishni, Buyurtmachi Ta’lim oluvchi
            o‘qish uchun belgilangan to‘lovni o‘z vaqtida amalga oshirishi, Ta’lim oluvchi esa tasdiqlangan o‘quv reja bo‘yicha
            darslarga to‘liq qatnashish va ta’lim olishni o‘z zimmalariga oladi. Ta’lim oluvchining ta’lim ma’lumotlari quyidagicha:
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            1.1. Образовательное учреждение обязуется предоставить образовательные услуги,
            Заказчик обязуется своевременно осуществить оплату за обучение, а Обучающийся обязуется полностью
            участвовать в занятиях согласно утвержденному учебному плану и получать образование. Данные об Обучающемся следующие:
        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            Ta’lim bosqichi:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            <b>Bakalavr</b>
        </td>

        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            Степень образования:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            <b>Бакалавриат</b>
        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            Ta’lim shakli:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            <b><?= $eduDirection->eduForm->name_uz ?></b>
        </td>

        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            Форма обучения:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            <b><?= $eduDirection->eduForm->name_ru ?></b>
        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            O‘qish muddati:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            <b><?= ikYearUz($eduDirection->duration) ?></b>
        </td>

        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            Срок обучения:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            <b><?= ikYearRu($eduDirection->duration) ?></b>
        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            O‘quv kursi:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            <?php if ($student->edu_type_id == 2) : ?>
                <b><?= Course::findOne(['id' => ($student->course_id + 1)])->name_uz ?></b>
            <?php else: ?>
                <b>1 kurs</b>
            <?php endif; ?>
        </td>

        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            Учебный курс:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            <?php if ($student->edu_type_id == 2) : ?>
                <b><?= Course::findOne(['id' => ($student->course_id + 1)])->name_ru ?></b>
            <?php else: ?>
                <b>1 - й курс</b>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            Ta’lim yo‘nalishi:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            <b><?= str_replace('.', '', $direction->code) . ' - ' . $direction->name_uz ?></b>
        </td>

        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            Направление обучения:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: center; border: 1px solid #000;">
            <b><?= str_replace('.', '', $direction->code) . ' - ' . $direction->name_ru ?></b>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            1.2 “Ta’lim muassasasi”ga o‘qishga qabul qilingan “Ta’lim oluvchi”lar O‘zbekiston Respublikasining “Ta’lim to‘g‘risida”gi Qonuni
            va davlat ta’lim standartlarga muvofiq ishlab chiqilgan o‘quv rejalar va fan dasturlari asosida ta’lim oladilar.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            1.2 Обучающиеся, принятые в Образовательное учреждение, получают образование на основе учебных планов и программ, разработанных
            в соответствии с Законом Республики Узбекистан "Об образовании"　и　государственными образовательными стандартами.
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>II. TA’LIM XIZMATINI KO‘RSATISH NARXI, TO‘LASH MUDDATI VA TARTIBI</b>
        </td>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>II. СТОИМОСТЬ ПРЕДОСТАВЛЯЕМЫХ ОБРАЗОВАТЕЛЬНЫХ УСЛУГ, СРОКИ И ПОРЯДОК ОПЛАТЫ</b>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            2.1. “Ta’lim muassasasi”da o‘qish davrida ta’lim xizmatini ko‘rsatish narxi
            Respublikada belgilangan Bazaviy hisoblash miqdori o’zgarishiga bog‘liq holda hisoblanadi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            2.1. Стоимость образовательных услуг в Образовательном учреждении в период
            обучения рассчитывается в зависимости от изменений базовой расчетной величины, установленной в Республике.
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            2.2. Ushbu shartnoma bo‘yicha ta’lim oluvchini bir yillik o‘qitish uchun to‘lov <b><?= number_format((int)$contract->contract_price, 0, '', ' ') ?></b> ( <b><?= Contract::numUzStr($contract->contract_price) ?> soʻm</b> )
            soʻmni tashkil etadi va quyidagi muddatlarda amalga oshiriladi:
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            2.2. Оплата за обучение Обучающегося за один учебный год по данному договору составляет <b><?= number_format((int)$contract->contract_price, 0, '', ' ') ?></b>
            ( <b><?= Contract::numRuStr($contract->contract_price) ?> сум</b> ) сумов и производится в следующие сроки:
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            Choraklarga bo‘lib to‘langanda quyidagi muddatlarda: <br>
            - belgilangan to‘lov miqdorining kamida 25.00 foizini talabalikka tavsiya etilgan abiturientlar uchun 15.09.2025 gacha, ikkinchi va undan yuqori bosqich talabalar uchun 01.10.2025 gacha; <br>
            -belgilangan to‘lov miqdorining kamida 50.00 foizini 01.12.2025 gacha, 75.00 foizini 01.03.2026 gacha va 100.00 foizini 01.05.2026 gacha.

        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            При оплате по кварталам в следующие сроки: <br>
            - не менее 25%　от установленной суммы абитуриентами, рекомендованными к зачислению, до 15.11.2025, для студентов второго и последующих курсов - до 25.11.2025;<br>
            - не менее 50% от установленной суммы до 01.01.2026, 75% до 01.04.2026, и 100% до 01.06.2026.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            2.3. Buyurtmachi tomonidan shartnoma to‘lovini to‘lashda shartnomaning tartib raqami va sanasi, familiyasi, ismi va sharifi
            hamda o‘quv kursi to‘lov topshiriqnomasida to‘liq ko‘rsatiladi. To‘lov kuni Universitetning bank hisob raqamiga mablag‘ kelib tushgan kun hisoblanadi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            2.3. При оплате по этому договору Заказчик должен указать в платежном поручении номер и дату договора, фамилию,
            имя и отчество, а также учебный курс. Дата оплаты считается днем поступления средств на банковский счет Образовательного учреждения.
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            2.4. Shartnoma Universitet tashabbusi bilan Talaba uning hatti-harakatlari
            (harakatsizligi) sababli talabalar safidan chetlashtirilsa, shartnoma bo‘yicha to‘langan mablag‘lar qaytarilmaydi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            2.4. В случае отчисления Обучающего по инициативе
            Образовательного учреждения из-за его действий (бездействия) оплаченные средства по договору не подлежат возврату.
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            2.5. Ushbu shartnomaning 2.2-bandida ko‘zda tutilgan to‘lov muddatlari Tomonlarning o’zaro kelishuvi bilan o’zgartrilishi mumkin.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            2.5. Сроки платежей, предусмотренные в пункте 2.2 данного договора, могут быть изменены по взаимному соглашению сторон.
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">

        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">

        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>III. TOMONLARNING MAJBURIYATLARI</b>
        </td>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>III. ОБЯЗАННОСТИ СТОРОН</b>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-bottom: 1px solid #fff;">
            3.1. <b>Ta’lim muassasasi majburiyatlari:</b> <br>
            - O‘qitish uchun belgilangan to‘lov o‘z vaqtida amalga oshirgandan so‘ng, “Ta’lim oluvchi”ni buyruq asosida talabalikka qabul qilish.<br>
            - Ta’lim oluvchiga o‘qishi uchun O‘zbekiston Respublikasining “Ta’lim to‘g‘risida”gi Qonuni va “Ta’lim muassasasi” Ustavida nazarda tutilgan zarur shart-sharoitlarga muvofiq sharoitlarni yaratib berish.<br>
            - Ta’lim oluvchining huquq va erkinliklari, qonuniy manfaatlari hamda ta’lim muassasasi Ustaviga muvofiq professor-o‘qituvchilar tomonidan o‘zlarining funksional vazifalarini to‘laqonli bajarishini ta’minlash.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-bottom: 1px solid #fff;">
            3.1. <b>Обязанности Образовательного учреждения:</b> <br>
            - После своевременного внесения установленной платы за обучение принять Обучающегося на учебу на основании приказа.<br>
            - Создать необходимые условия для обучения Обучающегося в соответствии с Законом Республики Узбекистан "Об образовании" и Уставом Образовательного учреждения.<br>
            - Обеспечить защиту прав, свобод и законных интересов Обучающегося, а также выполнение преподавателями и сотрудниками своих функциональных обязанностей в соответствии с Уставом образовательного учреждения.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-top: 1px solid #fff;">
            - Ta’lim oluvchini tahsil olayotgan ta’lim yo‘nalishi (mutaxassisligi) bo‘yicha tasdiqlangan o‘quv rejasi va dasturlariga muvofiq davlat ta’lim standarti talablari darajasida tayyorlash.
            - O‘quv yili boshlanishida ta’lim oluvchini yangi o‘quv yili uchun belgilangan to‘lov miqdori to‘g‘risida o‘quv jarayoni boshlanishidan oldin xabardor qilish.<br>
            - Respublikada belgilangan Mehnatga haq to‘lashning eng kam miqdori yoki tariflar o‘zgarishi natijasida o‘qitish uchun belgilangan to‘lov miqdori o‘zgargan taqdirda ta’lim oluvchiga ta’limning qolgan muddati uchun to‘lov miqdori haqida xabar berish.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-top: 1px solid #fff;">
            - Подготовить Обучающегося в соответствии с утвержденным учебным планом и программами по выбранному направлению (специальности) на уровне требований государственных образовательных стандартов.
            - Уведомить Обучающегося о сумме платы за новый учебный год до начала учебного процесса.<br>
            - В случае изменения минимального размера оплаты труда или тарифов в республике, уведомить Обучающегося о новой сумме платы за обучение на оставшийся период.
        </td>
    </tr>




    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            3.2. <b>Ta’lim oluvchining majburiyatlari:</b> <br>
            - Shartnomaning 2.2. bandida belgilangan to‘lov summasini shu bandda ko‘rsatilgan muddatlarda to‘lab borish.<br>
            - Respublikada belgilangan Mehnatga haq to‘lashning eng kam miqdori yoki tariflar o‘zgarishi natijasida o‘qitish uchun belgilangan to‘lov miqdori o‘zgargan taqdirda, o‘qishning qolgan muddati uchun ta’lim muassasasiga haq to‘lash bo‘yicha bir oy muddat ichida shartnomaga qo‘shimcha bitim rasmiylashtirish va to‘lov farqini to‘lash.<br>
            - Ta’lim oluvchi o‘qitish uchun belgilangan to‘lov miqdorini to‘laganlik to‘g‘risidagi bank tasdiqnomasi va shartnomaning bir nusxasini o‘z vaqtida hujjatlarni rasmiylashtirish uchun ta’lim muassasasiga topshirish.<br>
            - Tahsil olayotgan ta’lim yo‘nalishining (mutaxassisligining) tegishli malaka tavsifnomasiga muvofiq kelajakda mustaqil faoliyat yuritishga zarur bo‘lgan barcha bilimlarni egallash, dars va mashg‘ulotlarga to‘liq qatnashish.<br>
            - Ta’lim muassasasi va talabalar turar joyining ichki nizomlariga qa’tiy rioya qilish, professoro‘ qituvchilar va xodimlarga hurmat bilan qarash, “Ta’lim muassasasi” obro‘siga putur yetkazadigan harakatlar qilmaslik, moddiy bazasini asrash, ziyon keltirmaslik, ziyon keltirganda o‘rnini qoplash.<br>
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            3.2. <b>Обязанности Обучающегося:</b> <br>
            - Оплачивать сумму, указанную в пункте 2.2 договора, в сроки, установленные в данном пункте.<br>
            - В случае изменения минимального размера оплаты труда или тарифов в республике, оформить дополнительное соглашение к договору в течение одного месяца и оплатить разницу за оставшийся период обучения.<br>
            - Вовремя предоставить Oбразовательному учреждению банковское подтверждение об оплате установленной суммы за обучение и одну копию договора для оформления документов.<br>
            - Овладеть всеми необходимыми знаниями для будущей самостоятельной деятельности в соответствии с квалификационной характеристикой по выбранной специальности, полностью посещать занятия и учебные мероприятия.<br>
            - Строго соблюдать внутренние правила Oбразовательного учреждения и студенческого общежития, уважительно относиться к преподавателям и сотрудникам, не совершать действий, наносящих ущерб репутации Образовательного учреждения, бережно относиться к материальной базе, возмещать причиненный ущерб в случае его нанесения.
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>IV. TOMONLARNING HUQUQLARI</b>
        </td>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>IV. ПРАВА СТОРОН</b>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-bottom: 1px solid #fff;">
            4.1. <b>Ta’lim muassasasi huquqlari:</b> <br>
            - O‘quv jarayonini mustaqil ravishda amalga oshirish, “Ta’lim oluvchi”ning oraliq va yakuniy nazoratlarni topshirish, qayta topshirish tartibi hamda vaqtlarini belgilash.<br>
            - O‘zbekiston Respublikasi qonunlari, “Ta’lim muassasasi” nizomi hamda mahalliy normativ-huquqiy hujjatlarga muvofiq “Ta’lim oluvchi”ga rag‘batlantiruvchi yoki intizomiy choralarni qo‘llash.<br>
            - “Ta’lim oluvchi” shartnomaning 2.2-bandida ko‘rsatilgan to‘lov muddatlariga rioya qilmagan taqdirda, “Ta’lim muassasasi” uning nazorat imtihonlarida ishtirok etishiga ruxsat bermaslik huquqiga ega.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-bottom: 1px solid #fff;">
            4.1. <b>Права Образовательного учреждения:</b> <br>
            - Самостоятельно осуществлять учебный процесс, устанавливать порядок и сроки сдачи промежуточных и итоговых экзаменов, а также пересдач.<br>
            - Применять поощрительные или дисциплинарные меры к Обучающемуся в соответствии с законами Республики Узбекистан, Уставом Образовательного учреждения и местными нормативно-правовыми актами.<br>
            - В случае неисполнения «Обучающимся» сроков оплаты, указанных в пункте 2.2 настоящего договора, «Образовательное учреждение» имеет право не допустить его к участию в контрольных экзаменах.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-top: 1px solid #fff;">
            - Agar “Ta’lim oluvchi” o‘quv yili semestrlarida yakuniy nazoratlarni topshirish, qayta topshirish natijalariga ko‘ra akademik qarzdor bo‘lib qolsa uni kursdan-kursga qoldirish huquqiga ega.<br>
            - “Ta’lim muassasasi” “Ta’lim oluvchi”ning qobiliyati, darslarga sababsiz 36 akademik soat qatnashmaslik, intizomni buzish, “Ta’lim muassasasi”ning ichki tartib qoidalariga amal qilmaganda, respublikaning normativ-huquqiy hujjatlarida nazarda tutilgan boshqa sabablarga ko‘ra hamda o‘qitish uchun belgilangan to‘lov o‘z vaqtida amalga oshirilmaganda “Ta’lim oluvchi”ni talabalar safidan chetlashtirish huquqiga ega.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-top: 1px solid #fff;">
            - В случае, если Обучающийся не сдаст итоговые экзамены или пересдачи в течение семестра и будет иметь академическую задолженность, образовательное учреждение имеет право оставить его на повторное обучение на том же курсе.<br>
            - Образовательное учреждение имеет право отчислить Обучающегося в случае выявления неспособности, пропуска 36 академических часов занятий без уважительных причин, нарушения дисциплины, несоблюдения внутренних правил учреждения, по другим причинам, предусмотренным нормативно-правовыми актами Республики, а также за несвоевременную оплату за обучение.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-bottom: 1px solid #fff;">
            4.2. <b>Ta’lim oluvchining huquqlari:</b> <br>
            - O‘quv yili uchun shartnoma summasini semestrlarga yoki choraklarga bo‘lmasdan bir yo‘la to‘liqligicha to‘lash mumkin.<br>
            - Ta’lim oluvchi mazkur shartnoma bo‘yicha naqd pul, bank plastik kartasi, bankdagi omonat hisob raqami orqali, ish joyidan arizasiga asosan oylik maoshini o‘tkazishi yoki banklardan ta’lim krediti olish orqali to‘lovni amalga oshirishi mumkin.<br>
            - Professor-o‘qituvchilarning o‘z funksional vazifalarini bajarishidan yoki ta’lim muassasasidagi shart- sharoitlardan norozi bo‘lgan taqdirda ta’lim muassasasi rahbariyatiga yozma shaklda murojaat qilish.<br>
            - Quyidagi hollarda Ta’lim muassasasi ruxsati bilan 1 (bir) yilgacha akademik ta’til olish:<br>
            a) salomatlik holati davlat sog‘liqni saqlash tizimiga kiruvchi tibbiyot muassasalarining davolovchi shifokorlari tomonidan hujjatlar bilan tasdiqlangan sezilarli darajada yomonlashganda;<br>
            b) homiladorlik va tug‘ish, shuningdek bola ikki yoshga to‘lgunga qadar parvarishlash bo‘yicha ta’tilga bog‘liq hollarda;
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-bottom: 1px solid #fff;">
            4.2. <b>Права Обучающегося:</b> <br>
            - Обучающийся может оплатить полную сумму договора за учебный год сразу, без разделения на семестры или кварталы.<br>
            - Обучающийся может внести оплату по договору наличными, банковской картой, через банковский счет, путем перечисления с заработной платы по заявлению на рабочем месте или с помощью образовательного кредита.<br>
            - В случае недовольства выполнением функциональных обязанностей преподавателей или условиями в образовательном учреждении, обучающийся имеет право обратиться с письменным заявлением к руководству учреждения.<br>
            - В следующих случаях Обучающийся имеет право получить академический отпуск сроком до 1 (одного) года с разрешения образовательного учреждения:<br>
            а) при значительном ухудшении здоровья, подтвержденном документами лечащих врачей медицинских учреждений, входящих в государственную систему здравоохранения;<br>
            б) в случае беременности, родов и отпуска по уходу за ребенком до достижения им двухлетнего возраста;
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-top: 1px solid #fff;">
            v) yaqin qarindoshining vafoti munosabati bilan bu holda akademik ta’til berish Ta’lim muassasasi rahbariyati tomonidan har bir holat alohida ko‘rib chiqiladi va qaror qabul qilinadi;<br>
            g) harbiy xizmatni o‘tash uchun safarbar etilishi munosabati bilan;<br>
            d) boshqa hollarda Ta’lim muassasasi rahbariyatining qaroriga ko‘ra.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; border-top: 1px solid #fff;">
            в) в случае смерти близкого родственника (в этом случае академический отпуск предоставляется по решению руководства образовательного учреждения, после рассмотрения каждого случая отдельно);<br>
            г) при призыве на военную службу;<br>
            д) в других случаях по решению руководства образовательного учреждения.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            4.3. Buyurtmachining huquqlari:
            - Ta’lim oluvchi va Ta’lim muassasasidan shartnoma majburiyatlari bajarilishini talab qilish.
            - Ta’lim oluvchining Ta’lim muassasasi o‘quv reja va dasturlariga muvofiq ta’lim olishini nazorat qilish
            - Ta’lim muassasasi ta’lim jarayonlarini yaxshilashga doir takliflar berish.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000;">
            4.3. Права Заказчика:
            - Требовать от Обучающегося и образовательного учреждения выполнения обязательств по договору.
            - Контролировать процесс обучения Обучающегося в соответствии с учебными планами и программами образовательного учреждения.
            - Вносить предложения по улучшению учебного процесса Oбразовательного учреждения.
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>V. SHARTNOMANING AMAL QILISH MUDDATI, UNGA O‘ZGARTIRISH VA QO‘SHIMCHALAR KIRITISH HAMDA BEKOR QILISH TARTIBI</b>
        </td>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>V. СРОК ДЕЙСТВИЯ ДОГОВОРА, ПОРЯДОК ВНЕСЕНИЯ ИЗМЕНЕНИЙ И ДОПОЛНЕНИЙ, А ТАКЖЕ ПОРЯДОК РАСТОРЖЕНИЯ</b>
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            5.1 Ushbu shartnoma uch tomonlama imzolangandan so‘ng kuchga kiradi hamda ta’lim xizmatlarini taqdim etish o‘quv yili tugagunga qadar amalda bo‘ladi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            5.1. Настоящий договор вступает в силу после его подписания тремя сторонами и действует до окончания учебного года при предоставлении образовательных услуг.
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            5.2 Ushbu shartnoma shartlariga uch tomon kelishuviga asosan tuzatish, o‘zgartirish va qo‘shimchalar kiritilishi mumkin
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            5.2. В настоящий договор могут быть внесены изменения, дополнения и корректировки по взаимному согласию трех сторон.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            5.3 Shartnomaga tuzatish, o‘zgartirish va qo‘shimchalar faqat yozma ravishda “Shartnomaga qo‘shimcha bitim” tarzida kiritiladi va imzolanadi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            5.3. Изменения, дополнения и корректировки вносятся в письменной форме в виде "Дополнительного соглашения к договору" и подписываются обеими сторонами.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            5.4 Shartnoma quyidagi hollarda bekor qilinishi mumkin: <br>
            - Tomonlarning o‘zaro kelishuviga binoan.<br>
            - “Ta’lim muassasasi” tashabbusi bilan “Ta’lim oluvchi” talabalar safidan chetlashtirilganda Buyurtmachini ogohlantirgan holda bir tomonlama bekor qilinishi mumkin.<br>
            - Ta’lim muassasasiga Buyurtmachi tomonidan to’lov o’z vaqtida va to’liq amalga oshirilmaganda;<br>
            - Tomonlardan biri o‘z majburiyatlarini bajarmaganda yoki lozim darajada bajarmaganda sud qarori asosida
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            5.4. Договор может быть расторгнут в следующих случаях:<br>
            - По взаимному согласию сторон.<br>
            - В случае отчисления Обучающегося из числа студентов, договор может быть расторгнут в одностороннем порядке по инициативе Образовательного учреждения с предварительным уведомлением Заказчика.<br>
            - В случае, если Образовательное учреждение не получает своевременную и полную оплату от Заказчика.<br>
            - В случае невыполнения или ненадлежащего выполнения одной из сторон своих обязательств на основании решения суда.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            5.5. Ta’lim muassasasi tugatilganda, ta’lim oluvchi bilan o‘zaro qayta hisob-kitob qilinadi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            5.5. При прекращении деятельности Образовательного учреждения производится взаимный расчет с Обучающимся.
        </td>
    </tr>



    <tr>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>VI. YAKUNIY QOIDALAR VA NIZOLARNI HAL QILISH TARTIBI</b>
        </td>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>VI. ЗАКЛЮЧИТЕЛЬНЫЕ ПОЛОЖЕНИИ И ПОРЯДОК РЕШЕНИЯ СПОРОВ</b>
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.1 Ta’lim muassasasining Ta’lim oluvchini o‘qishga qabul qilish buyrug‘i Ta’lim oluvchi
            tomonidan barcha kerakli hujjatlarni taqdim etish va to‘lovni amalga oshirish sharti bilan chiqariladi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.1. Приказ о зачислении Обучающегося в образовательное
            учреждение выдается при условии предоставления всех необходимых документов и оплаты с его стороны.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.2 Ta’lim oluvchiga Ta’lim muassasasi tomonidan stipendiya to‘lanmaydi va Ta’lim
            muassasasi Ta’lim oluvchini ishga joylashtirish majburiyatini o‘z zimmasiga olmaydi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.2. Обучающемуся не выплачивается стипендия, и образовательное учреждение
            не принимает на себя обязательства по трудоустройству Обучающегося.
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.3 Ushbu shartnomani bajarish jarayonida kelib chiqishi mumkin bo‘lgan nizo va
            ziddiyatlar tomonlar o‘rtasida muzokaralar olib borish yo‘li bilan hal etiladi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.3. Споры и конфликты, возникающие в процессе выполнения настоящего договора, решаются путем переговоров между сторонами.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.4 Muzokaralar olib borish yo‘li bilan nizoni hal etish imkoniyati bo‘lmagan taqdirda, tomonlar nizolarni hal etish
            uchun amaldagi qonunchilikka muvofiq Ta’lim muassasasi joylashgan yerdagi sudga murojaat etishlari mumkin.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.4. Если разрешение спора путем переговоров невозможно, стороны могут обратиться
            в суд по месту нахождения Образовательного учреждения в соответствии с действующим законодательством для решения спора.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.5 Ta’lim muassasasi tomonidan ushbu shartnoma bo‘yicha mablag‘lar qaytarilishi lozim bo‘lgan hollarda
            mazkur mablag‘lar tegishli hujjat o‘z kuchiga kirgan paytdan boshlab 30 (o‘ttiz) kun ichida qaytariladi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.5. В случаях, когда образовательное учреждение обязано вернуть средства по данному договору, эти средства
            будут возвращены в течение 30 (тридцати) дней с момента вступления в силу соответствующего документа.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.6 Ushbu Shartnomaga kiritilgan har qanday o‘zgartirish va/yoki qo‘shimchalar, agar ular
            tomonlar tomonidan yozma shaklda rasmiylashtirilgan, imzolangan/muhrlangan bo‘lsagina amal qiladi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.6. Любые изменения и/или дополнения к настоящему Договору действуют только в том случае, если они
            оформлены в письменной форме и подписаны/заверены сторонами.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.7 “Ta’lim muassasasi” axborotlar va xabarnomalarni internetdagi veb-saytida, axborot
            tizimida yoki e’lonlar taxtasida e’lon joylashtirish orqali xabar berishi mumkin.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.7. Образовательное　учреждение　может информировать о своих действиях и уведомлениях путем размещения информации
            на своем веб-сайте, в информационных системах или на информационных стендах.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.8 Shartnoma 3 (uch) nusxada, tomonlarning har biri uchun bir nusxadan tuzildi va uch nusxa ham bir xil huquqiy kuchga ega.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.8. Договор составлен в 3 (трех) экземплярах, по одному для каждой стороны, и все три экземпляра имеют равную юридическую силу.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.9 Ushbu Shartnomaga qo‘shimcha bitim kiritilgan taqdirda ushbu barcha kiritilgan qo‘shimcha bitimlar shartnomaning ajralmas qismi hisoblanadi.
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            6.9. В случае внесения дополнительных соглашений, все такие соглашения считаются неотъемлемой частью настоящего Договора.
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>VII. TOMONLARNING REKVIZITLARI VA IMZOLARI</b>
        </td>
        <td colspan="2" style="text-align: center;  border: 1px solid #000;">
            <b>VII. РЕКВИЗИТЫ И ПОДПИСИ СТОРОН</b>
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            7.1 Ta’lim muassasasi: <b><?= $filial->name_uz ?></b> <br>
            <b>Manzil:</b> <?= $filial->address_uz ?> <br>
            Bank rekvizitlari:<br>
            <b>H/R: </b> <?= $number ?> <br>
            <b>Bank: </b> <?= $bankUz ?> <br>
            <b>Bank kodi (MFO): </b> <?= $mfo ?> <br>
            <b>STIR (INN): </b> <?= $inn ?> <br>
            <b>Telefon: </b> +998 78 888 22 88 <br>
            <img src="<?= $img ?>" width="120px">
        </td>
        <td colspan="2" style="text-align: justify; vertical-align: top; border: 1px solid #000; ">
            7.1. Образовательное учреждение: <b><?= $filial->name_uz ?></b> <br>
            <b>Адрес: </b> <?= $filial->address_ru ?> <br>
            Банковские реквизиты:<br>
            <b>Расчетный счет: </b> <?= $number ?> <br>
            <b>Банк: </b> <?= $bankRu ?> <br>
            <b>Код банка (МФО): </b> <?= $mfo ?><br>
            <b>ИНН: </b> <?= $inn ?> <br>
            <b>Тел: </b> +998 78 888 22 88 <br>
            <img src="<?= $limg ?>" width="120px"> <br>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="text-align: left; vertical-align: center; border: 1px solid #000; ">

        </td>
        <td colspan="2" style="text-align: right; vertical-align: center; border: 1px solid #000; ">
            <b>Дата и номер лицензии</b>
            14.09.2024 <b>№ 397374</b>
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: left; vertical-align: center; border: 1px solid #000; ">
            <b>7.2. Ta’lim oluvchi:</b>
        </td>
        <td colspan="2" style="text-align: left; vertical-align: center; border: 1px solid #000; ">
            <b>7.2. Обучающийся:</b>
        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            F.I.Sh.:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            <b
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Ф.И.О.:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            <b
        </td>
    </tr>


    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Pasport ma’lumotlari:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            <?= $student->passport_serial . ' ' . $student->passport_number ?>
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Данные паспорта:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            <?= $student->passport_serial . ' ' . $student->passport_number ?>
        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            JShShIR:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            <?= $student->passport_pin ?>
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            ПИНФЛ:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            <?= $student->passport_pin ?>
        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Telefon raqami:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            <?= $student->user->username ?>
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Номер телефона:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            <?= $student->user->username ?>
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: left; vertical-align: center; border: 1px solid #000; ">
            <b>7.3. Buyurtmachi:</b>
        </td>
        <td colspan="2" style="text-align: left; vertical-align: center; border: 1px solid #000; ">
            <b>7.3. Заказчик:</b>
        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Yuridik shaxs:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Юридическое лицо:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Manzil:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Адрес:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            H/r:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Расчетный счет:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Bank:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Банк:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            MFO:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            МФО:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            STIR:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            ИНН:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Tel:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Телефон:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            E-mail:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Электронная почта:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
    </tr>

    <tr>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Rahbar:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">
            Руководитель:
        </td>
        <td colspan="1" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: left; vertical-align: top; border: 1px solid #000; ">

        </td>
    </tr>


</table>