import React, { Component } from 'react'
import Axios from 'axios';

import Card from './Card/Card';
import CardCreate from './CardCreate/CardCreate';

class Column extends Component {
    constructor(props) {
        super(props)
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
                    {this.props.title}
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
