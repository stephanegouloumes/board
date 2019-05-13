import React, { Component } from 'react'
import Axios from 'axios';

import Card from './Card/Card';
import CardCreate from './CardCreate/CardCreate';

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
            this.props.cards.map(card => {
                return <Card key={card.id} {...card} selectCard={() => this.props.selectCard(card.id)} />
            })
        ) : null

        return (
            <div className="c-column">
                <div className="c-column__header">
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
                    {cards}
                    <CardCreate onAddCard={this.props.onAddCard} />
                </div>
            </div>
        )
    }
}

export default Column
