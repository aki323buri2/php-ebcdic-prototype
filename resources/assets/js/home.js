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
				<td key={name} className={name}>{row[name]}</td>
			)}
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
]);
const reducer = handleActions({
	SKELETON: (state, { payload }) => ({ ...state, rows: payload }), 
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
});