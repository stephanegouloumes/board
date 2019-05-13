import React, { Component } from 'react'
import Axios from 'axios';

import Column from './Column/Column';
import ColumnCreate from './ColumnCreate/ColumnCreate';
import CardModal from './Column/CardModal/CardModal';

class Board extends Component {
    constructor(props) {
        super(props)

        this.state = { columns: [] }
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
                columns: [...this.state.columns, JSON.parse(response.data)]
            })
        })
        .catch(error => {
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

            this.setState({ columns })
        })
        .catch(error => {
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
    }

    removeColumn = (columnId) => {
        Axios.delete('/column/' + columnId)
        .then(response => {
            const columns = this.state.columns.filter(column => column.id !== columnId)

            this.setState({ columns })
        })
        .catch(error => {
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

            this.setState({ columns })

            this.unselectCard()
        })
        .catch(error => {
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
                return <Column key={column.id} {...column} onAddCard={(title) => this.addCard(column.id, title)} selectCard={(cardId) => this.selectCard(column.id, cardId)} />
            })
        ) : null

        return (
            <div className="c-board">
                {columns}
                <ColumnCreate onAddColumn={this.addColumn} />
                <CardModal cardSelected={this.state.cardSelected} updateCard={this.updateCard} removeCard={this.removeCard} unselectCard={this.unselectCard} />
            </div>
        )
    }
}

export default Board
