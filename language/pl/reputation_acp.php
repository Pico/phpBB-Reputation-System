<?php
/**
*
* Reputation System
*
* @copyright (c) 2014 Lukasz Kaczynski
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_REPUTATION_SETTINGS_EXPLAIN'	=> 'Tutaj możesz skonfigurować ustawienia systemu reputacji. Są one podzielone na grupy.',
	'ACP_REPUTATION_RATE_EXPLAIN'		=> 'Tutaj możesz przyznawać dodatkowe punkty reputacji dowolnym użytkownikom.',

	'RS_ENABLE'						=> 'Włącz system reputacji',

	'RS_SYNC'						=> 'Zsynchronizuj system reputacji',
	'RS_SYNC_EXPLAIN'				=> 'Możesz zsynchronizować punkty reputacji, po masowym usunięciu postów/tematów/użytkowników, zmienić ustawienia reputacji, zmienić autorów postów, konwersje z innych systemów. To może chwilę potrwać. Zostaniesz powiadomiony, kiedy proces się zakończy.<br /><strong>Uwaga!</strong> Wszystkie punkty reputacji, które nie pasują do ustawień reputacji, będą usunięte podczas synchronizacji. Zaleca się zrobić backup tabeli reputacji (DB) przed synchronizacją.',
	'RS_SYNC_REPUTATION_CONFIRM'	=> 'Jesteś pewien, że chcesz zsynchronizować reputację?',

	'RS_TRUNCATE'				=> 'Wyczyść system reputacji',
	'RS_TRUNCATE_EXPLAIN'		=> 'Ta procedura całkowicie usuwa dane.<br /><strong>Po wykonaniu, jest niemożliwa do cofnięcia!</strong>',
	'RS_TRUNCATE_CONFIRM'		=> 'Jesteś pewien, że chcesz wyczyścić system reputacji?',
	'RS_TRUNCATE_DONE'			=> 'Reputacje wyczyszczono.',

	'REPUTATION_SETTINGS_CHANGED'	=> '<strong>Zmieniono ustawienia systemu reputacji</strong>',

	// Setting legend
	'ACP_RS_MAIN'			=> 'Ogólne',
	'ACP_RS_DISPLAY'		=> 'Ustawienia wyświetlania',
	'ACP_RS_POSTS_RATING'	=> 'Oceny postów',
	'ACP_RS_USERS_RATING'	=> 'Oceny użytkowników',
	'ACP_RS_COMMENT'		=> 'Komentarze',
	'ACP_RS_POWER'			=> 'Moc reputacji',
	'ACP_RS_TOPLIST'		=> 'Toplista',

	// General
	'RS_NEGATIVE_POINT'				=> 'Zezwalaj na punkty negatywne',
	'RS_NEGATIVE_POINT_EXPLAIN'		=> 'Kiedy wyłączone, użytkownicy nie mogą przyznawać negatywnych punktów.',
	'RS_MIN_REP_NEGATIVE'			=> 'Minimalna reputacja dla negatywnej oceny',
	'RS_MIN_REP_NEGATIVE_EXPLAIN'	=> 'Ile punktów reputacji jest wymagane do przyznawania punktów negatywnych. Ustawienie wartości na 0 wyłącza to ustawienie.',
	'RS_WARNING'					=> 'Zezwalaj na ostrzeżenia',
	'RS_WARNING_EXPLAIN'			=> 'Użytkownicy z odpowiednimi przywilejami mogą przyznawać negatywne punkty podczas ostrzegania użytkowników.',
	'RS_WARNING_MAX_POWER'			=> 'Maksymalna moc reputacji dla ostrzeżeń',
	'RS_WARNING_MAX_POWER_EXPLAIN'	=> 'Maksymalna moc reputacji przyzwolona dla ostrzeżeń.',
	'RS_MIN_POINT'					=> 'Minimum punktów',
	'RS_MIN_POINT_EXPLAIN'			=> 'Określa minimum punktów reputacji, które może otrzymać użytkownik. Ustawienie wartości na 0 wyłącza to ustawienie.',
	'RS_MAX_POINT'					=> 'Maksimum punktów',
	'RS_MAX_POINT_EXPLAIN'			=> 'Określa maksimum punktów reputacji, które moze otrzymać użytkownik. Ustawienie wartości na 0 wyłącza to ustawienie.',
	'RS_PREVENT_OVERRATING'			=> 'Zapobieganie przecenianiu',
	'RS_PREVENT_OVERRATING_EXPLAIN'	=> 'Odmawia użytkownikowi oceniania tego samego użytkownika.<br /><em>Przykład:</em> jeżeli użytkownik ma więcej niż 10 punktów reputacji i 85% z nich są od użytkownika B, użytkownik B nie moze oceniać tego użytkownika, póki stosunek jego ocen jest wyższy niż 85%.<br />By wyłączyć tę opcję, ustaw jedną lub obie wartości na 0.',
	'RS_PREVENT_NUM'				=> 'Łączne wystapienia reputacji użytkownika A są równe lub wyższe',
	'RS_PREVENT_PERC'				=> '<br />oraz stosunek ocen użytkownika B jest równy lub wyższy',
	'RS_PER_PAGE'					=> 'Reputacje na stronę',
	'RS_PER_PAGE_EXPLAIN'			=> 'Ile wierszy powinno być wyświetlane w tabelkach punktów reputacji?',
	'RS_DISPLAY_AVATAR'				=> 'Pokaż awatary',
	'RS_POINT_TYPE'					=> 'Metoda wyświetlania punktów',
	'RS_POINT_TYPE_EXPLAIN'			=> 'Widok punktów reputacji może być wyświetlany zarówno w dokładnej wartości punktów reputacji użytkownik przyznał lub jako obrazek przedstawiający plus lub minus dla pozytywnych lub negatywnych punktów. Metoda obrazka jest przydatna, jeżeli ocena = 1 punkt.',
	'RS_POINT_VALUE'				=> 'Wartość',
	'RS_POINT_IMG'					=> 'Obrazek',

	// Post rating
	'RS_POST_RATING'				=> 'Włącz ocenę postów',
	'RS_POST_RATING_EXPLAIN'		=> 'Pozwól użytkownikom oceniać posty innych użytkowników.<br />Na każdej stronie zarządzania forum możesz włączyć lub wyłączyć reputacje.',
	'RS_ALLOW_REPUTATION_BUTTON'	=> 'Włącz system reputacji na wszystkich forach',
	'RS_ANTISPAM'					=> 'Anty-Spam',
	'RS_ANTISPAM_EXPLAIN'			=> 'Opcja ta uniemożliwia ocenianie postów użytkownikom, którzy ocenili za dużą liczbę postów w ciągu ostatnich godzin. By wyłączyć tę opcję, ustaw jedną lub obie wartości na 0.',
	'RS_POSTS'						=> 'Ilość ocenionych postów',
	'RS_HOURS'						=> 'w ostatnich godzinach',
	'RS_ANTISPAM_METHOD'			=> 'Metoda sprawdzania Anty-Spamu',
	'RS_ANTISPAM_METHOD_EXPLAIN'	=> 'Metoda sprawdzania Anty-Spamu. Metoda “Ten sam użytkownik” sprawdza reputację przyznaną temu samemu użytkownikowi. Metoda “Wszyscy użytkownicy” sprawdza reputację bez względu na to, kto otrzymał punkty.',
	'RS_SAME_USER'					=> 'Ten sam użytkownik',
	'RS_ALL_USERS'					=> 'Wszyscy użytkownicy',

	// User rating
	'RS_USER_RATING'				=> 'Włącz ocenę użytkownika',
	'RS_USER_RATING_GAP'			=> 'Przerwa w ocenianiu',
	'RS_USER_RATING_GAP_EXPLAIN'	=> 'Okres czasu, w którym użytkownik nie może ocenić tego samego użytkownika. Ustawienie wartości na 0 wyłącza tę opcję i użytkownicy mogą się ocenić tylko raz.',

	// Comments
	'RS_ENABLE_COMMENT'				=> 'Włącz komentarze',
	'RS_ENABLE_COMMENT_EXPLAIN'		=> 'Kiedy włączone, użytkownicy będą mogli dodawać własne komentarze wraz z oceną.',
	'RS_FORCE_COMMENT'				=> 'Zmuś użytkownika do wprowadzenia komentarza',
	'RS_FORCE_COMMENT_EXPLAIN'		=> 'Przy wystawianiu oceny, użytkownik będzie musiał wystawić komentarz.',
	'RS_COMMENT_NO'					=> 'Nie',
	'RS_COMMENT_BOTH'				=> 'Zarówno przy ocenie użytkownika, jak i postu',
	'RS_COMMENT_POST'				=> 'Tylko przy ocenie posta',
	'RS_COMMENT_USER'				=> 'Tylko przy ocenie użytkownika',
	'RS_COMMEN_LENGTH'				=> 'Długość komentarza',
	'RS_COMMEN_LENGTH_EXPLAIN'		=> 'Ilość znaków dozwolona w komentarzu. Ustaw 0 dla braku limitu.',

	// Reputation power
	'RS_ENABLE_POWER'				=> 'Włącz moc reputacji',
	'RS_ENABLE_POWER_EXPLAIN'		=> 'Moc reputacji to coś, co użytkownicy zyskują i wykorzystują na ocenianie. Nowi użytkownicy mają niską moc, aktywni użytkownicy oraz weterani zyskują większą moc. Im więcej masz mocy, tym więcej możesz oceniać w trakcie ustalonego odstępu czasu i tym większy wpływ masz na ocenę innego użytkownika lub posta.<br/>Użytkownicy mogą wybrać, ile mocy podczas oceniania zużyją na ocenę, dając więcej punktów interesującym postom.',
	'RS_POWER_RENEWAL'				=> 'Czas odnowienia mocy',
	'RS_POWER_RENEWAL_EXPLAIN'		=> 'Ta opcja kontroluje, w jaki sposób użytkownicy mogą wykorzystać zebraną moc.<br/>Jeżeli ustawisz tę opcję, użytkownicy muszą zaczekać odpowiednią ilość czasu, zanim będą mogli znów oceniać. Im więcej mocy reputacji użytkownik posiada, tym więcej punktów może zużyć w określonym czasie.<br />Ustawienie wartości na 0 wyłącza tę opcję i użytkownicy mogą oceniać bez czekania.',
	'RS_MIN_POWER'					=> 'Startowa/Minimalna moc reputacji',
	'RS_MIN_POWER_EXPLAIN'			=> 'Wyznacza ile mocy reputacji mają nowo zarejestrowani użytkownicy, zbanowani użytkownicy oraz użytkownicy z niską reputacją lub mający inne kryteria. Uzytkownicy nie mogą zejść poniżej tej minimalnej mocy oceniania.<br/>Dozwolone 0-10. Zalecane 1.',
	'RS_MAX_POWER'					=> 'Maksymalna moc do zużycia na ocenę',
	'RS_MAX_POWER_EXPLAIN'			=> 'Maksymalna ilość mocy, która użytkownik może zużyć na głos. Nawet jeśli użytkownik ma miliony punktów, może wciąż być limitowany przez tę wartość podczas oceniania.<br/>Użytkownicy wybiorą tę wartość z menu rozwijanego: od 1 do X<br/>Dozwolone 1-20. Zalecane: 3.',
	'RS_POWER_EXPLAIN'				=> 'Wyjaśnienie mocy reputacji',
	'RS_POWER_EXPLAIN_EXPLAIN'		=> 'Wyjasnia, jak moc reputacji jest obliczana dla użytkowników.',
	'RS_TOTAL_POSTS'				=> 'Zyskanie mocy wraz z ilością postów',
	'RS_TOTAL_POSTS_EXPLAIN'		=> 'Użytkownik zyska 1 moc reputacji co X napisanych postów tu ustawionych.',
	'RS_MEMBERSHIP_DAYS'			=> 'Zyskanie mocy wraz z długością służby użytkownika',
	'RS_MEMBERSHIP_DAYS_EXPLAIN'	=> 'Użytkownik zyska 1 moc reputacji co X dni tu ustawionych',
	'RS_POWER_REP_POINT'			=> 'Zyskanie mocy z reputacji użytkownika',
	'RS_POWER_REP_POINT_EXPLAIN'	=> 'Użytkownik zyska 1 moc reputacji co X punktów reputacji które uzyskali tu ustawionych.',
	'RS_LOSE_POWER_WARN'			=> 'Utrata mocy z ostrzeżeniami',
	'RS_LOSE_POWER_WARN_EXPLAIN'	=> 'Każde ostrzeżenie zmniejsza moc reputacji o określoną ilość punktów. Ostrzeżenia przestają działać po czasie zgodnym z ustawieniami w Ogólne -> Konfiguracja Witryny -> Ustawienia witryny',

	// Toplist
	'RS_ENABLE_TOPLIST'				=> 'Włącz toplistę',
	'RS_ENABLE_TOPLIST_EXPLAIN' 	=> 'Wyświetla listę użytkowników z największą liczbą punktów reputacji na głównej stronie.',
	'RS_TOPLIST_DIRECTION'			=> 'Kierunek listy',
	'RS_TOPLIST_DIRECTION_EXPLAIN'	=> 'Wyświetla użytkowników na liście poziomo lub pionowo.',
	'RS_TL_HORIZONTAL'				=> 'Poziomo',
	'RS_TL_VERTICAL'				=> 'Pionowo',
	'RS_TOPLIST_NUM'				=> 'Ilość użytkowników do wyświetlenia',
	'RS_TOPLIST_NUM_EXPLAIN'		=> 'Ilość użytkowników wyświetlana na topliście.',

	// Rate module
	'POINTS_INVALID'	=> 'Pole na punkty może zawierać tylko cyfry.',
	'RS_VOTE_SAVED'		=> 'Twoja ocena została zapisana',
));
