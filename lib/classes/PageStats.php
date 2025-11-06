<?php
class PageStats
{
	public $start, $end, $time_diff;
	
	public function __construct()
	{
		$this->start = microtime(true);
	}
	
	public function output_result()
	{
		$this->end = microtime(true);
		
		$this->time_diff = $this->end - $this->start;
		
		$html = '
				<style type="text/css">
					.page_stats_table {
						font-family: Arial, Verdana, sans-serif;
						font-size:12px;
						border-collapse: collapse;
						width:80%;
						margin:0 10% 0 10%;
					}
					.page_stats_table thead td {
						text-align:center;
						background: #ccc;
					}
					.page_stats_table tbody td {
						text-align:center;
					}
					.page_stats_table tbody td:first-child {
						text-align:left;
					}
					.page_stats_table td {
						border:1px solid #333;
					}
				</style>
				<table class="page_stats_table" cellspacing="0" cellpadding="0" border="0">
					<thead>
						<tr>
							<td>Query</td>
							<td>Status</td>
							<td>Affected</td>
							<td>Time</td>
						</tr>
					</thead>
					<tbody>
		';
		
		if( isset($_SESSION['sql_log']) && is_array($_SESSION['sql_log']) )
		{
			foreach($_SESSION['sql_log'] as $k => $v)
			{
				$html .= '
						<tr>
							<td>'.$v['sql'].'</td>
							<td>'.$v['status'].'</td>
							<td>'.$v['affected'].'</td>
							<td>'.$v['time'].'</td>
						</tr>
				';
			}
		}
		
		$html .= '
						<tr>
							<td colspan="3">Site time</td>
							<td>'.$this->time_diff.'</td>
						</tr>
					<tbody>
				</table>
		';
		
		return $html;
	}
}