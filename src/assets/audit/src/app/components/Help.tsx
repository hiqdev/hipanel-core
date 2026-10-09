import React from "react";
import { Alert, Tag } from "antd";

export const Help = () => (
  <Alert
    type={"info"}
    showIcon
    closable
    style={{ marginBottom: 16 }}
    message={"How to read this log"}
    description={
      <ul style={{ margin: 0, paddingLeft: 18 }}>
        <li><b>Group header</b>: changes made by a single request (same trace) are gathered under one header with their time, user and operation counts.</li>
        <li><b>Highlighted rows</b> with a blue left border belong to the group above. Rows without a header are standalone changes.</li>
        <li><b>Entity</b>: for properties of an object (table <code>value</code>) the property name is shown, with the table and row id below it.</li>
        <li><b>Table</b>: a <span className={"fa fa-link fa-fw"}></span> icon marks a change of a nested object, not of the object itself.</li>
        <li><b>Operation</b>: <Tag color={"green"}>CREATE</Tag><Tag color={"geekblue"}>UPDATE</Tag><Tag color={"volcano"}>DELETE</Tag>. For updated properties the line below shows <code>old → new</code>.</li>
        <li><b>Filters</b> above the table search by property, value, entity id or table, and narrow rows by date, user, operation and, on an object page, <i>Direct</i> (the object itself) or <i>Nested</i> (its properties and related objects).</li>
        <li><b>+</b> expands a row to see the full before/after diff and request details. The trace link opens all changes of the request.</li>
      </ul>
    }
  />
);
