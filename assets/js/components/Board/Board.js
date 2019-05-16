import React, { Component } from 'react'
import Axios from 'axios'
import { DragDropContext, Droppable } from 'react-beautiful-dnd'

import Column from './Column/Column'
import ColumnCreate from './ColumnCreate/ColumnCreate'
import CardModal from './Column/CardModal/CardModal'

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
        Axios.post('/board/' + this.props.board.id + '/column', { title, position: this.state.columns.length + 1 })
        .then(response => {
            this.setState({
                columns: [...this.state.columns, JSON.parse(response.data)],
            })

            this.props.showToast('Column created')
            this.props.recordActivity('Column', JSON.parse(response.data).id, 'created')
        })
        .catch(error => {
            this.props.showToast(error.message)
            console.log(error)
        })
    }

    addCard = (columnId, title) => {
        let position = 1
        this.state.columns.forEach(column => {
            if (column.id === columnId) {
                position = column.cards.length + 1
            }
        })

        Axios.post('/board/' + this.props.board.id + '/column/' + columnId + '/card', { title, position })
        .then(response => {
            const columns = this.state.columns.filter(column => {
                if (column.id === columnId) {
                    column.cards.push(JSON.parse(response.data))
                }

                return column
            })

            this.setState({ columns })

            this.props.recordActivity('Card', JSON.parse(response.data).id, 'created')
            this.props.showToast('Card created')
        })
        .catch(error => {
            this.props.showToast(error.message)
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
        this.props.recordActivity('Column', columnId, 'updated')

        Axios.patch('/board/' + this.props.board.id + '/column/' + columnId, { title: value })
            .then(response => {
            })
            .catch(error => {
                this.props.showToast(error.message)
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

        this.props.recordActivity('Card', this.state.cardSelected.id, 'updated')

        Axios.patch('/board/' + this.props.board.id + '/column/' + this.state.columnSelected.id + '/card/' + this.state.cardSelected.id, this.state.cardSelected)
        .then(response => {
        })
        .catch(error => {
            this.props.showToast(error.message)
            console.log(error)
        })
    }

    updateColumns = (columns) => {
        Axios.patch('/board/' + this.props.board.id + '/columns', columns)
            .then(response => {
            })
            .catch(error => {
                this.props.showToast(error.message)
                console.log(error)
            })
    }

    updateCards = (cards) => {
        Axios.patch('/board/' + this.props.board.id + '/cards', cards)
            .then(response => {
            })
            .catch(error => {
                this.props.showToast(error.message)
                console.log(error)
            })
    }

    removeColumn = (columnId) => {
        console.log('ok')
        Axios.delete('/board/' + this.props.board.id + '/column/' + columnId)
        .then(response => {
            const columns = this.state.columns.filter(column => column.id !== columnId)

            this.setState({ columns })

            this.props.recordActivity('Column', 0, 'removed')
            this.props.showToast('Column removed', 'error')
        })
        .catch(error => {
            this.props.showToast(error.message)
            console.log(error)
        })
    }

    removeCard = () => {
        Axios.delete('/board/' + this.props.board.id + '/column/' + this.state.columnSelected.id + '/card/' + this.state.cardSelected.id)
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

            this.props.recordActivity('Card', 0, 'removed')
            this.props.showToast('Card removed', 'error')

            this.unselectCard()
        })
        .catch(error => {
            this.props.showToast(error.message)
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

    onDragEnd = ({ destination, source, draggableId, type }) => {
        if (! destination || destination.droppableId === source.droppableId && destination.index === source.index) {
            return
        }

        if (type === 'column') {
            this.reorderColumns(destination, source)
        } else {
            this.reorderCards(destination, source)
        }
    }

    reorderColumns = (destination, source) => {
        const columns = this.state.columns

        const movedColumn = columns.splice(source.index, 1)[0]
        columns.splice(destination.index, 0, movedColumn)

        const updatedColumns = []
        columns.forEach((column, index) => {
            if (column.position !== index + 1) {
                column.position = index + 1
                updatedColumns.push({ id: column.id, position: column.position })
            }
        })

        this.setState({ columns })

        this.updateColumns(updatedColumns)
    }

    reorderCards = (destination, source) => {
        const startColumn = this.state.columns.filter(column => column.id === source.droppableId)[0]
        const endColumn = this.state.columns.filter(column => column.id === destination.droppableId)[0]

        const movedCard = startColumn.cards.splice(source.index, 1)[0]
        endColumn.cards.splice(destination.index, 0, movedCard)

        const updatedCards = []
        startColumn.cards.forEach((card, index) => {
            if (card.position !== index + 1) {
                card.position = index + 1
                updatedCards.push({ id: card.id, position: card.position })
            }
        })

        if (startColumn !== endColumn) {
            endColumn.cards.forEach((card, index) => {
                if (card.id === movedCard.id) {
                    card.position = index + 1
                    updatedCards.push({ id: card.id, position: card.position, card_list_id: endColumn.id })
                } else if (card.position !== index + 1) {
                    card.position = index + 1
                    updatedCards.push({ id: card.id, position: card.position })
                }
            })
        }

        const columns = this.state.columns.map((column, index) => {
            if (index === source.droppableId - 1) {
                return startColumn
            } else if (index === destination.droppableId - 1) {
                return endColumn
            }

            return column
        })

        this.setState({ columns })

        this.updateCards(updatedCards)
    }
    
    render() {
        const columns = this.state.columns ? (
            this.state.columns.map((column, index) => {
                return <Column key={column.id} index={index} {...column} onAddCard={(title) => this.addCard(column.id, title)} updateColumn={(type, value) => this.updateColumn(column.id, type, value)} removeColumn={() => this.removeColumn(column.id)} selectCard={(cardId) => this.selectCard(column.id, cardId)} />
            })
        ) : null

        return (
            <DragDropContext onDragEnd={this.onDragEnd}>
                <Droppable droppableId="all-columns" direction="horizontal" type="column">
                    {provided => (
                        <div className="c-board" ref={provided.innerRef} {...provided.droppableProps}>
                            {columns}
                            {provided.placeholder}
                            <ColumnCreate onAddColumn={this.addColumn} />
                            <CardModal cardSelected={this.state.cardSelected} updateCard={this.updateCard} removeCard={this.removeCard} unselectCard={this.unselectCard} />
                        </div>
                    )}
                </Droppable>
            </DragDropContext>
        )
    }
}

export default Board
