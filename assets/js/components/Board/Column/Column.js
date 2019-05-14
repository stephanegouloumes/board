import React, { Component } from 'react'
import { Draggable, Droppable } from 'react-beautiful-dnd'

import Card from './Card/Card'
import CardCreate from './CardCreate/CardCreate'

class Column extends Component {
    constructor(props) {
        super(props)

        this.state = {
            optionsShown: false
        }
    }

    componentWillMount() {
        document.addEventListener('mousedown', this.handleComponentClick, false)
    }

    componentWillUnmount() {
        document.removeEventListener('mousedown', this.handleComponentClick, false)
    }

    handleComponentClick = (e) => {
        if (this.node.contains(e.target)) {
            return
        }

        this.setState({ optionsShown: false })
    }

    showOptions = () => {
        this.setState({ optionsShown: true })
    }

    render() {
        const cards = this.props.cards ? (
            this.props.cards.map((card, index) => {
                return <Card key={card.id} index={index} {...card} selectCard={() => this.props.selectCard(card.id)} />
            })
        ) : null

        return (
            <Draggable draggableId={'col-' + this.props.id} index={this.props.index}>
                {provided => (
                    <div className="c-column" ref={provided.innerRef} {...provided.draggableProps}>
                        <div className="c-column__header" {...provided.dragHandleProps}>
                            <input className="c-column__title" type="text" value={this.props.title} onChange={(e) => this.props.updateColumn('title', e.target.value)} />
                            <span onClick={this.openOptions} onClick={this.showOptions} ref={node => this.node = node}><i className="fas fa-ellipsis-h"></i></span>
                            <div className={this.state.optionsShown ? 'c-column__options' : 'c-column__options is-hidden'}>
                                <ul>
                                    <li>Archive</li>
                                    <li onClick={this.props.removeColumn}>Delete</li>
                                </ul>
                            </div>
                        </div>
                        <div className="c-column__content">
                            <Droppable droppableId={this.props.id} type="card">
                                {provided => (
                                    <div ref={provided.innerRef} {...provided.droppableProps}>
                                        {cards}
                                        {provided.placeholder}
                                    </div>
                                )}
                            </Droppable>
                            <CardCreate onAddCard={this.props.onAddCard} />
                        </div>
                    </div>
                )}
            </Draggable>
        )
    }
}

export default Column
