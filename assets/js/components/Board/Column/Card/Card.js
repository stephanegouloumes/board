import React, { Component } from 'react'
import { Draggable } from 'react-beautiful-dnd'

class Card extends Component {
    constructor(props) {
        super(props)
    }

    render() {
        return (
            <Draggable draggableId={this.props.id} index={this.props.index}>
                {(provided) => (
                    <div className="c-card" onClick={this.props.selectCard} ref={provided.innerRef} {...provided.draggableProps} {...provided.dragHandleProps}>
                        <div className="c-card__header">
                            {this.props.title}
                        </div>
                        {this.props.description &&
                            <div className="c-card__content">
                                {this.props.description}
                            </div>
                        }
                    </div>
                )}
            </Draggable>
        )
    }
}

export default Card
