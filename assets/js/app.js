
import React from 'react'
import ReactDOM from 'react-dom'

import App from './components/App/App.js'

require('../css/app.scss');

if (document.getElementById('js-board') !== null) {
    const board = JSON.parse(document.getElementById('js-board').dataset.entryId)

    ReactDOM.render(<App board={board} />, document.getElementById('js-board'))
}