
import React from 'react'
import ReactDOM from 'react-dom'

import Board from './components/Board/Board.js'

require('../css/app.scss');

if (document.getElementById('js-board') !== null) {
    const board = JSON.parse(document.getElementById('js-board').dataset.entryId)

    ReactDOM.render(<Board board={board} />, document.getElementById('js-board'))
}