<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
	'REPUTATION_SYSTEM'				=> 'Sistema de Reputação',

	'ACP_REPUTATION_SYSTEM'				=> 'Sistema de Reputação',
	'ACP_REPUTATION_SYSTEM_EXPLAIN'		=> 'A partir daqui você pode visualizar todas as configurações e funções do Sistema de Reputação necessárias para gerenciá-lo.',
	'ACP_REPUTATION_OVERVIEW'			=> 'Visão Geral',
	'ACP_REPUTATION_SETTINGS'			=> 'Configurações',
	'ACP_REPUTATION_SETTINGS_EXPLAIN'	=> 'Nesta página você pode configurar todas as funções do sistema de reputação. Tudo está dividido em grupos.',
	'ACP_REPUTATION_GIVE'				=> 'Conceder ponto',
	'ACP_REPUTATION_RANKS'				=> 'Ranks',
	'MCP_REPUTATION'					=> 'Reputação',
	'MCP_REPUTATION_FRONT'				=> 'Página Inicial',
	'MCP_REPUTATION_LIST'				=> 'Listar reputações',
	'MCP_REPUTATION_GIVE'				=> 'Conceder ponto',
	'UCP_REPUTATION'					=> 'Reputação',
	'UCP_REPUTATION_FRONT'				=> 'Página inicial',
	'UCP_REPUTATION_LIST'				=> 'Listar',
	'UCP_REPUTATION_GIVEN'				=> 'Conceder pontos',
	'UCP_REPUTATION_SETTING'			=> 'Configurações',

	'ACP_RS_MAIN'			=> 'Geral',
	'ACP_RS_DISPLAY'		=> 'Display',
	'ACP_RS_POSTS_RATING'	=> 'Posts rating',
	'ACP_RS_USERS_RATING'	=> 'Users rating',
	'ACP_RS_COMMENT'		=> 'Comments',
	'ACP_RS_POWER'			=> 'Reputation power',
	'ACP_RS_RANKS'			=> 'Ranks',
	'ACP_RS_TOPLIST'		=> 'Toplist',

	'RS_ENABLE'		=> 'Habilitar sistema de reputação',

	'RS_NEGATIVE_POINT'				=> 'Permitir pontos negativos',
	'RS_NEGATIVE_POINT_EXPLAIN'		=> 'Quando usuários desabilitados não podem conceder pontos negativos.',
	'RS_MIN_REP_NEGATIVE'			=> 'Reputação mínima para conceder pontos negativos',
	'RS_MIN_REP_NEGATIVE_EXPLAIN'	=> 'Quanto de reputação é necessário para conceder pontos negativos. Configurando o valor em 0 desabilita este comportamento.',
	'RS_WARNING'					=> 'Habilitar avisos',
	'RS_WARNING_EXPLAIN'			=> 'Usuários com permissões adequadas podem conceder pontos negativos quando estiverem avisando usuários.',
	'RS_NOTIFICATION'				=> 'Habilitar notificações',
	'RS_NOTIFICATION_EXPLAIN'		=> 'Esta opção habilita notificações sobre novos pontos de reputação no cabeçalho.',
	'RS_PM_NOTIFY'					=> 'Habilita notificação via mensagem privada',
	'RS_PM_NOTIFY_EXPLAIN'			=> 'Esta opção permite usuários a enviar uma notificação via mensagem privada quando há novos pontos de reputação.',
	'RS_MIN_POINT'					=> 'Mínimo de pontos',
	'RS_MIN_POINT_EXPLAIN'			=> 'Limita o mínimo de pontos de reputação que um usuário vai receber. Configurar este valor em 0 desabilita este comportamento.',
	'RS_MAX_POINT'					=> 'Máximo de pontos',
	'RS_MAX_POINT_EXPLAIN'			=> 'Limita o máximo de pontos que um usuário pode receber. Configurar este valor em 0 desabilita este comportamento.',
	'RS_PREVENT_OVERRATING'			=> 'Prevenindo o 'overrating' ( super avalição do usuário )',
	'RS_PREVENT_OVERRATING_EXPLAIN'	=> 'Bloquear usuários a avaliar o mesmo usuário.<br /><em>Exemplo:</em> se o usuário A tem mais de 10 pontos de reputação e 85% vem do usuário B, o usuário B não pode qualificar este usuário enquanto sua relação de votos for maior que 85%.<br />Para desabilitar esta função é só configurar um ou todos os valores como 0.',
	'RS_PREVENT_NUM'				=> 'O total de entradas de reputação do usuário A é igual ou maior que',
	'RS_PREVENT_PERC'				=> '<br />e a relalão dos votos do usuário B é igual ou maior a',

	'RS_PER_PAGE'							=> 'Reputações por página',
	'RS_PER_PAGE_EXPLAIN'					=> 'Quantas linhas nós devemos mostrar na sua tabela de reputações ?',
	'RS_DISPLAY_AVATAR'						=> 'Mostrar avatares',
	'RS_SORT_MEMBERLIST_BY_REPO'			=> 'Ordenar a lista de membros por reputação',
	'RS_SORT_MEMBERLIST_BY_REPO_EXPLAIN'	=> 'Quando a lista de membros está sendo ordenada por reputação é mais sensato checar ela regularmente para ver como tudo está andando. Você pode voltar esta configuração para 'off' ( desligado ) para voltar ao padrão que é ordenar por nome de usuário.',
	'RS_POINT_TYPE'							=> 'Método para mostrar os pontos',
	'RS_POINT_TYPE_EXPLAIN'					=> 'A visualização dos pontos de reputação pode ser feita a partir do valor exato de pontos de reputação que o usuário recebeu ou uma imagem mostrando mais ou menos para ponto positivos ou negativos. O método de imagem é mais usual se você configurou o envio dos pontos de reputação de para um ( ou seja, um ponto por vez ).',
	'RS_POINT_VALUE'						=> 'Valor',
	'RS_POINT_IMG'							=> 'Imagem',

	'RS_POST_RATING'				=> 'Habilitar avaliação de posts',
	'RS_ALLOW_REPUTATION_BUTTON'	=> 'Submeter e habilitar o Sistema de Reputação em todos os forums',
	'RS_HIGHLIGHT_POST'				=> 'Destacando um post',
	'RS_HIGHLIGHT_POST_EXPLAIN'		=> 'Um post com uma avaliação maior que o número configurado aqui será destacado. Configurando o valor para 0 esta funcionalidade está desabilitada.<br /><em>Nota:</em> Se você quer modificar o destaque padrão é só editar  a classe  <strong>highlight</strong> no arquivo reputation.css.',
	'RS_HIDE_POST'					=> 'Esconder posts com poucas avaliações',
	'RS_HIDE_POST_EXPLAIN'			=> 'Post com uma avaliação menor que a configurada aqui serão escondidos por padrão ( os usuários tem a opção de mostrá-los caso achem interessante ). Assim que um post ganhar pontos de reputação maiores que este valor, ele não mais estará escondido por padrão. Configurar este valor para 0 desabilita esta funcionalidade.',
	'RS_ANTISPAM'					=> 'Anti-Spam',
	'RS_ANTISPAM_EXPLAIN'			=> 'Bloquear usuários de avaliar mais posts depois que alcançarem o número post definido aqui dentro de um período de horas. Para desabilitar esta funcionalidade configure um ou mais valores para 0.',
	'RS_POSTS'						=> 'Número de posts avaliados',
	'RS_HOURS'						=> 'nas últimas horas',
	'RS_ANTISPAM_METHOD'			=> 'Método de checagem do anti-spam,
	'RS_ANTISPAM_METHOD_EXPLAIN'	=> 'Método de chegagem do Anti-Spam. “Mesmo usuário” é o método que testa a reputação enviada para o mesmo usuário. “Todos usuários” este método que testa a reputação a partir de quem recebeu os pontos.',
	'RS_SAME_USER'					=> 'Mesmo usuário',
	'RS_ALL_USERS'					=> 'Todos usuários',

	'RS_USER_RATING'				=> 'Permitir que os usuários sejam avaliados a partir da sua página de perfil',
	'RS_USER_RATING_GAP'			=> 'Gap para votação',
	'RS_USER_RATING_GAP_EXPLAIN'	=> 'Período de tempo que um usuário deve esperar para enviar outra avaliação para um usuário que já tenha sido avaliado. Configur o valor para 0 desabilita esta funcionalidade e usuários podem avaliar outros usuários uma vez cada.',

	'RS_ENABLE_COMMENT'				=> 'Habilitar comentários',
	'RS_ENABLE_COMMENT_EXPLAIN'		=> 'Quando habilitado, usuários podem adicionar um comentário pessoal em conjunto com sua avaliação.',
	'RS_FORCE_COMMENT'				=> 'Forçar usuário a entrar um comentário',
	'RS_FORCE_COMMENT_EXPLAIN'		=> 'Usuários são obrigados a adicionar um comentário em conjunto com sua avaliação.',
	'RS_COMMENT_NO'					=> 'Não',
	'RS_COMMENT_BOTH'				=> 'Ambas avaliações de usuários e posts',
	'RS_COMMENT_POST'				=> 'Somente avaliações de post',
	'RS_COMMENT_USER'				=> 'Somente avaliações de usuario',
	'RS_COMMEN_LENGTH'				=> 'Tamanho do comentário',
	'RS_COMMEN_LENGTH_EXPLAIN'		=> 'O número de caracteres permitido em um comentário. Configure para 0 para caracteres ilimitados.',

	'RS_ENABLE_POWER'				=> 'Ativar força de reputação',
	'RS_ENABLE_POWER_EXPLAIN'		=> 'Força de reputação é algo que os usuários ganham e enviam quando votam. Novos usuários tem uma força pequena, usuários ativos e veteranos ganham mais poder. Maior poder você terá quando votar em um período específico de tempo e a maior influência você terá votando em um períoodo de tempo em outro usuário ou post.<br/>Usuários podem escolher enquanto estiverem votando quanta força de reputação usarão quando estiverem votando, enviando mais pontos para posts interessantes.',
	'RS_POWER_RENEWAL'				=> 'Tempo de renovação da força',
	'RS_POWER_RENEWAL_EXPLAIN'		=> 'Isto controla quanto os usuários podem gastar a força que ganharam.<br/>Se você configurar esta opção, usuários terão que esperar pelo intervalo de tempo para votar novamente. Quanto mais poder de reputação um usuário tem, mais pontos ele poderá usar neste período configurado.<br/>O recomendado são 5 horas.<br />Configurando a valor em 0 esta funcionalidade é desabilitada e usuários poderão votar sem esperar.',
	'RS_MIN_POWER'					=> 'Inicial/Mínimo força de reputação',
	'RS_MIN_POWER_EXPLAIN'			=> 'Quanto poder de reputação um usuário recebe quando se registrar no forum, quando um usuário é banido e usuários com reputação baixa e outros critérios tem. Usuários não podem ir além deste mínimo ou não conseguirão votar.<br/>Permitido 0-10. Recomendado 1.',
	'RS_MAX_POWER'					=> 'Máximo de força utilizada por voto',
	'RS_MAX_POWER_EXPLAIN'			=> 'Máximo de força que vai ser utilizada por usuário ao votar. Se um usuário tem milhões de pontos, ele está limitado a este máximo quando estiver votando.<br/>Usuário selecionarão isto a partir de um menu: 1 to X<br/>Permitido 1-20. Recomendado: 3.',
	'RS_MAX_POWER_WARNING'			=> 'Máximo de força de reputação para avisos',
	'RS_MAX_POWER_WARNING_EXPLAIN'	=> 'Máximo de força de reputação permitida para avisos.',
	'RS_MAX_POWER_BAN'				=> 'Máximo de força de reputação para banimentos',
	'RS_MAX_POWER_BAN_EXPLAIN'		=> 'Máximo de pontos de reputação que um usuário irá ganhar caso tenha sido banido por 1 mês ou permanentamente. Se um usuário é banido por um perído menor de tempo, ele irá receber o valor relativo aquele tempo.',
	'RS_POWER_EXPLAIN'				=> 'Explicação da força de reputação',
	'RS_POWER_EXPLAIN_EXPLAIN'		=> 'Explica como a força de reputação é calculada aos usuários.',
	'RS_TOTAL_POSTS'				=> 'Ganha força com o número de posts',
	'RS_TOTAL_POSTS_EXPLAIN'		=> 'Usuário ganha 1 ponto de força de reputação a cada X posts configurados aqui.',
	'RS_MEMBERSHIP_DAYS'			=> 'Ganha poder de reputação pelo período que é membro',
	'RS_MEMBERSHIP_DAYS_EXPLAIN'	=> 'Usuário ganha um ponto de força de reputação a cada X dias configurados aqui',
	'RS_POWER_REP_POINT'			=> 'Ganha força de reputação pela reputação de usuário',
	'RS_POWER_REP_POINT_EXPLAIN'	=> 'Usuário ganha um ponto de reputação a cada número X de pontos de reputação configurados aqui.',
	'RS_LOSE_POWER_BAN'				=> 'Perde força com banimentos',
	'RS_LOSE_POWER_BAN_EXPLAIN'		=> 'Cada banimento no último ano retira força de reputação a partir deste montante de pontos',
	'RS_LOSE_POWER_WARN'			=> 'Perde força com avisos',
	'RS_LOSE_POWER_WARN_EXPLAIN'	=> 'Todos os avisos retiram força de reputação a partir deste montante de pontos. Avisos expiram respeitando a configuração em Geral -> Configurações do forum -> Configurações do forum',
	'RS_GROUP_POWER'				=> 'Força de reputação do grupo',

	'RS_RANKS_ENABLE'				=> 'Habilitar os ranks',
	'RS_RANKS_PATH'					=> 'Path onde as imagens do rank de reputação ficarão',
	'RS_RANKS_PATH_EXPLAIN'			=> 'Caminho no seu diretório do phpBB, e.g. <samp>images/reputation</samp>.',

	'RS_ENABLE_TOPLIST'				=> 'Habilitar Toplist',
	'RS_ENABLE_TOPLIST_EXPLAIN' 	=> 'Mostra uma lista de usuários com maior número de pontos de reputação na página principal.',
	'RS_TOPLIST_DIRECTION'			=> 'Direção da lista',
	'RS_TOPLIST_DIRECTION_EXPLAIN'	=> 'Mostra os usuário em uma lista na direção vertical ou horizontal.',
	'RS_TL_HORIZONTAL'				=> 'Horizontal',
	'RS_TL_VERTICAL'				=> 'Vertical',
	'RS_TOPLIST_NUM'				=> 'Número de usuários a serem mostrados',
	'RS_TOPLIST_NUM_EXPLAIN'		=> 'Número de usuários mostrados na toplist.',

	'RS_SYNC'						=> 'Resincronizar reputações',
	'RS_SYNC_EXPLAIN'				=> 'Você pode resincronizar o Sistema de Reputação após uma remoão de posts/usuários/tópicos, quando muda as configurações de reputação, quando modifica os autores de posts, conversões de outros sistemas. Isto leva algum tempo. Você será notificado quando o processo estiver completo.<br /><strong>Aviso!</strong> Durante a sincronização pontos de reputação serão deletados quando não forem condizentes com as configurações de reputação. é recomendado que seja feito um backup da tabela de reputação (BD) anes da sincronização..',
	'RS_SYNC_STEP_DEL'				=> 'Passo 1/7 - removendo os pontos de usuários não existentes',
	'RS_SYNC_STEP_POSTS_DEL'		=> 'Passo 2/7 - removendo os ponto de reputação de posts deletados',
	'RS_SYNC_STEP_REPS_DEL'			=> 'Passo 3/7 - remove reputações que não batem com as configurações de reputação',
	'RS_SYNC_STEP_POST_AUTHOR'		=> 'Passo 4/7 - checando o autor de um post e sincronizando o ponto de reputação se houve mudanças',
	'RS_SYNC_STEP_FORUM'			=> 'Passo 5/7 - checando as configurações do forum e sincronizando a influência de reputação dos posts na reputação do usuário',
	'RS_SYNC_STEP_USER'				=> 'Passo 6/7 - sincronização das reputações dos usuários',
	'RS_SYNC_STEP_POSTS'			=> 'Passo 7/7 - sincronização das reputações dos posts',
	'RS_SYNC_DONE'					=> 'Sincronização do Sistema de Reputação foi finalizada com sucesso',
	'RS_RESYNC_REPUTATION_CONFIRM'	=> 'Você tem certeza que quer resincronizar as reputações?',

	'RS_TRUNCATE'				=> 'Limpar o Sistema de Reputação',
	'RS_TRUNCATE_EXPLAIN'		=> 'Este procedimento remove todo o conteúdo.<br /><strong>Esta ação não é reversível!</strong>',
	'RS_TRUNCATE_CONFIRM'		=> 'Você tem certeza que quer limpar o Sistema de Reputação?',
	'RS_TRUNCATE_DONE'			=> 'Reputações foram limpas.',

	'RS_GIVE_POINT'				=> 'Enviar pontos de reputação',
	'RS_GIVE_POINT_EXPLAIN'		=> 'Aqui você pode enviar pontos adicionais de reputação aos usuários.',

	'RS_RANKS'					=> 'Gerenciar os ranks',
	'RS_RANKS_EXPLAIN'			=> 'Aqui você pode adicionar, editar, ver e deletar ranks baseado nos pontos de reputação. ',
	'RS_ADD_RANK'				=> 'Adicionar Rank',
	'RS_MUST_SELECT_RANK'		=> 'Você tem que selecionar um rank',
	'RS_NO_RANK_TITLE'			=> 'Você tem que especificfar um nome para o rank',
	'RS_RANK_ADDED'				=> 'O rank foi adicionado com sucesso.',
	'RS_RANK_MIN'				=> 'Mínimo de pontos',
	'RS_RANK_TITLE'				=> 'Título do rank',
	'RS_RANK_IMAGE'				=> 'Imagem do rank',
	'RS_RANK_COLOR'				=> 'Cor do rank',
	'RS_RANK_UPDATED'			=> 'O rank foi atualizado com sucesso.',
	'RS_IMAGE_IN_USE'			=> '(Em uso)',
	'RS_RANKS_ON'				=> '<span style="color:green;">Ranks de reputação estão ligados.</span>',
	'RS_RANKS_OFF'				=> '<span style="color:red;">Ranks de reputação estão desligados.</span>',
	'RS_NO_RANKS'				=> 'Não há ranks de reputação',

	'RS_FORUM_REPUTATION'			=> 'Habilitar reputação',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Permitir usuário a avaliar os posts. Você pode escolher se a avaliação dos posts vai influenciar na reputação do usuário.',
	'RS_POST_WITH_USER'				=> 'Sim, com influência no sistema de reputação',
	'RS_POST_WITHOUT_USER'			=> 'Sim, sem influência na reputação do usuário',

	'LOG_REPUTATION_SETTING'		=> '<strong>Configurações do sistema de reputação foram modificadas</strong>',
	'LOG_REPUTATION_SYNC'			=> '<strong>Sistema de reputação foi resincronizado</strong>',
	'LOG_REPUTATION_TRUNCATE'		=> '<strong>Reputações limpas</strong>',
	'LOG_RS_RANK_ADDED'				=> '<strong>Novo rank de reputação foi adicionado</strong><br />» %s',
	'LOG_RS_RANK_REMOVED'			=> '<strong>Rank de reputação foi removido</strong><br />» %s',
	'LOG_RS_RANK_UPDATED'			=> '<strong>Rank de reputação foi atualizado</strong><br />» %s',
	'LOG_USER_REP_DELETE'			=> '<strong>O ponto de reputação do usuário foi deletado</strong><br />Usuário: %s',
	'LOG_CLEAR_POST_REP'			=> '<strong>Reputação do post foi limpa</strong><br />Post: %s',
	'LOG_CLEAR_USER_REP'			=> '<strong>Reputação do usuário foi limpa</strong><br />Usuário: %s',

	'IMG_ICON_RATE_GOOD'		=> 'Avaliar como bom',
	'IMG_ICON_RATE_BAD'			=> 'Avaliar como ruim',

	//Installation
	'FILES_NOT_EXIST'		=> 'Os ícones de avaliação:<br />%s<br /> não foi encontrado.<br /><br /><strong>Antes de continuar, você precisa copiar os ícones de avalição no diretório <em>contrib/images</em> do diretório de imageset do estilo que você está usando. Aí, faça o refresh da página.<strong>',
	'CONVERTER'				=> 'Converter',
	'CONVERT_THANKS'		=> 'Converter o Thanks for posts para o sistema de reputação',
	'CONVERT_KARMA'			=> 'Converter o karma MOD para o sistema de reputação',
	'CONVERT_HELPMOD'		=> 'Converter o HelpMOD para o sistema de reputação',
	'CONVERT_LIKE'			=> 'Converter o phpBB Ajax Like para o sistema de reputação',
	'CONVERT_THANK'			=> 'Converter o módulo 'Thank You' para o sistema de Reputação',
	'CONVERT_DATA'			=> 'MOD convertido: %1$s.<br />Agora, você pode desinstalar %2$s. Vá ao ACP e efetue a resincronização do sistema de reputação.',
	'UPDATE_RS_TABLE'		=> 'A tabela de reputação foi atualizada.', 

	//MOD Version Check
	'ANNOUNCEMENT_TOPIC'		=> 'Anúncio de lançamento',
	'CURRENT_VERSION'			=> 'Versão atual',
	'DOWNLOAD_LATEST'			=> 'Fazer o download da última versão',
	'LATEST_VERSION'			=> 'Última versão',
	'NO_INFO'					=> 'O servidor de versão não pode ser contactado',
	'NOT_UP_TO_DATE'			=> '%s não está atualizado',
	'RELEASE_ANNOUNCEMENT'		=> 'Annoucement Topic',
	'UP_TO_DATE'				=> '%s está atualizado',
	'VERSION_CHECK'				=> 'Checador de versão do MOD',
));

?>
