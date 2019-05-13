import React, { Component } from 'react'

class Card extends Component {
    constructor(props) {
        super(props)
    }

    render() {
        return (
            <div className="c-card" onClick={this.props.selectCard}>
                <div className="c-card__header">
                    {this.props.title}
                </div>
                {this.props.description &&
                    <div className="c-card__content">
                        {this.props.description}
                    </div>
                }
            </div>
        )
    }
}

export default Card
