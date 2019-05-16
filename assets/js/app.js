
import React from 'react'
import ReactDOM from 'react-dom'

import App from './components/App/App.js'

require('../css/app.scss');

const boardElement = document.getElementById('js-board')

if (boardElement) {
    try {
        ReactDOM.render(<App board={JSON.parse(boardElement.dataset.entryBoard)} user={JSON.parse(boardElement.dataset.entryUser)} />, boardElement)
    } catch (error) {
        console.log(error)
    }
}