import React, { Component } from 'react'
import Axios from 'axios';

import Column from './Column/Column';
import ColumnCreate from './ColumnCreate/ColumnCreate';
import CardModal from './Column/CardModal/CardModal';
import Toast from './Toast/Toast';

class Board extends Component {
    constructor(props) {
        super(props)

        this.state = { columns: [], message: '' }
    }

    componentDidMount() {
        this.getColumns()
    }

    getColumns = () => {
        Axios.get('/board/' + this.props.board.id + '/column')
        .then(response => {
            this.setState({ columns: JSON.parse(response.data) })
        })
        .catch(error => {
            console.log(error)
        })
    }

    addColumn = (title) => {
        Axios.post('/board/' + this.props.board.id + '/column', { title })
        .then(response => {
            this.setState({
                columns: [...this.state.columns, JSON.parse(response.data)],
                message: 'Column created'
            })
        })
        .catch(error => {
            this.setState({ message: error.message })
            console.log(error)
        })
    }

    updateColumn = (columnId, type, value) => {
        const columns = this.state.columns.map(column => {
            if (column.id === columnId) {
                column[type] = value
            }

            return column
        })

        this.setState({ columns })
        Axios.put('/board/' + this.props.board.id + '/column/' + columnId, { title: value })
        .then(response => {
        })
        .catch(error => {
            this.setState({ message: error.message })
            console.log(error)
        })
    }

    addCard = (columnId, title) => {
        Axios.post('/column/' + columnId + '/card', { title })
        .then(response => {
            const columns = this.state.columns.filter(column => {
                if (column.id === columnId) {
                    column.cards.push(JSON.parse(response.data))
                }

                return column
            })

            this.setState({ columns, message: 'Card created' })
        })
        .catch(error => {
            this.setState({ message: error.message })
            console.log(error)
        })
    }

    updateCard = (type, value) => {
        const columns = this.state.columns.map(column => {
            if (column.id === this.state.columnSelected.id) {
                const updatedColumn = column

                updatedColumn.cards = column.cards.map(card => {
                    if (card.id === this.state.cardSelected.id) {
                        card[type] = value
                    }

                    return card
                })

                return updatedColumn
            }

            return column
        })

        this.setState({ columns })

        Axios.put('/column/' + this.state.columnSelected.id + '/card/' + this.state.cardSelected.id, this.state.cardSelected)
        .then(response => {
        })
        .catch(error => {
            this.setState({ message: error.message })
            console.log(error)
        })
    }

    removeColumn = (columnId) => {
        Axios.delete('/column/' + columnId)
        .then(response => {
            const columns = this.state.columns.filter(column => column.id !== columnId)

            this.setState({ columns, message: 'Column removed' })
        })
        .catch(error => {
            this.setState({ message: error.message })
            console.log(error)
        })
    }

    removeCard = () => {
        Axios.delete('/column/' + this.state.columnSelected.id + '/card/' + this.state.cardSelected.id)
        .then(response => {
            const columns = this.state.columns.map(column => {
                if (column.id === this.state.columnSelected.id) {
                    const updatedColumn = column
                    
                    updatedColumn.cards = column.cards.filter(card => {
                        return card.id !== this.state.cardSelected.id
                    })

                    return updatedColumn
                }

                return column
            })

            this.setState({ columns, message: 'Card removed' })

            this.unselectCard()
        })
        .catch(error => {
            this.setState({ message: error.message })
            console.log(error)
        })
    }

    selectCard = (columnId, cardId) => {
        let columnSelected = null
        let cardSelected = null

        this.state.columns.forEach(column => {
            if (column.id === columnId) {
                columnSelected = column
                column.cards.forEach(card => {
                    if (card.id === cardId) {
                        cardSelected = card
                        return    
                    }
                })
            }
        })

        this.setState({ columnSelected, cardSelected })
    }

    unselectCard = () => {
        this.setState({ cardSelected: null })
    }
    
    render() {
        const columns = this.state.columns ? (
            this.state.columns.map(column => {
                return <Column key={column.id} {...column} onAddCard={(title) => this.addCard(column.id, title)} updateColumn={(type, value) => this.updateColumn(column.id, type, value)} removeColumn={() => this.removeColumn(column.id)} selectCard={(cardId) => this.selectCard(column.id, cardId)} />
            })
        ) : null

        return (
            <div className="c-board">
                {columns}
                <ColumnCreate onAddColumn={this.addColumn} />
                <CardModal cardSelected={this.state.cardSelected} updateCard={this.updateCard} removeCard={this.removeCard} unselectCard={this.unselectCard} />
                <Toast message={this.state.message} />
            </div>
        )
    }
}

export default Board
