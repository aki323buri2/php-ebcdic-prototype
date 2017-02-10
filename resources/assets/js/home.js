const columns = {
	syozok : { title: '所属CD' }, 
	bukame : { title: '部署名' }, 
	ukin   : { title: '売上金額' }, 
	genkak : { title: '原価金額' }, 
	arari  : { title: '粗利益' }, 
	ritsu  : { title: '利率(%)' }, 
};

const rows = [

	{ syozok: 170, bukame: '水産１課' }, 
	{ syozok: 150, bukame: '水産２課' }, 
	{ syozok: 131, bukame: '水産３課' }, 
	{ syozok: 141, bukame: '水産４課' }, 
	{ syozok: 160, bukame: '日配１課' }, 
	{ syozok: 134, bukame: '日配２課' }, 
	{ syozok: 161, bukame: '日配３課' }, 
	{ syozok: 610, bukame: '東日本水産' }, 
	{ syozok: 620, bukame: '東日本日配' }, 
	{ syozok: 710, bukame: '山陰量販' }, 
	{ syozok: 830, bukame: '中部水産' }, 
	{ syozok: 910, bukame: '西日本水産１課' }, 
	{ syozok: 920, bukame: '西日本水産２課' }, 
	{ syozok: 930, bukame: '西日本テナント' }, 
	
];

const Table = ({
	columns, 
	rows, 
}) => (
	<table className="table is-bordered">
		<thead>
			<tr>
			{Object.entries(columns).map(([ name, { title } ]) =>
				<th key={name} className={name}>{title}</th>
			)}
			</tr>
		</thead>
		<tbody>
		{rows.map((row, i) =>
			<tr key={i}>
			{Object.entries(columns).map(([ name ]) =>
				<td key={name} className={name}>{row[name]}</td>
			)}
			</tr>
		)}
		</tbody>
	</table>
);
const app = <Table columns={columns} rows={rows} />;

import React from 'react';
import { render } from 'react-dom';

render(app, document.querySelector('#app'));