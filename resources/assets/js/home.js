const columns = {
	syozok : { title: '所属CD' }, 
	bukame : { title: '部署名' }, 
	ukin   : { title: '売上金額' }, 
	genkak : { title: '原価金額' }, 
	arari  : { title: '粗利益' }, 
	ritsu  : { title: '利率(%)' }, 
};

const rows = [];

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
			{
				const value = row[name];
				return <td key={name} className={name}>{value}</td>
			})}
			</tr>
		)}
		</tbody>
	</table>
);

/**
 * action creators and reducer
 */
import { createActions } from 'redux-actions';
import { handleActions } from 'redux-actions';
const actions = createActions(...[
	'SKELETON', 
	'REPLACE', 
]);
const reducer = handleActions({
	SKELETON: (state, { payload }) => ({ ...state, rows: payload }), 
	REPLACE : (state, { payload }) =>
	{
		const { rows } = state;
		const { where, replace } = payload;
		const before = rows[where];
		const after = {...before, ...replace};
		const inserted = [...rows.slice(0, where), after, ...rows.slice(where + 1)];

		return {...state, rows: inserted};
	}
}, { columns, rows });
/**
 * store
 */
import { createStore, applyMiddleware } from 'redux';
import logger from 'redux-logger';
const store = createStore(reducer, applyMiddleware(logger()));
/**
 * container
 */
import { Provider, connect } from 'react-redux';
const App = connect(state => state, actions)(Table);
const app = <Provider store={store}><App /></Provider>
/**
 * render
 */
import React from 'react';
import { render } from 'react-dom';
render(app, document.querySelector('#app'));

/**
 * fetch
 */
fetch('/home/skeleton').then(res => res.json()).then(json => 
{
	store.dispatch(actions.skeleton(json));
	return json;
})
.then(json => 
{
	json.map((row, i) =>
	{
		store.dispatch(actions.replace({ where: i, replace: { ukin: 1000000, genkak: 800000 } }));
	});
})
;