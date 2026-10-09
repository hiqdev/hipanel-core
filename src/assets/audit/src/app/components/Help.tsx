import React from "react";
import { Collapse, Tag } from "antd";

export const Help = () => (
  <Collapse
    size={"small"}
    style={{ marginBottom: 16 }}
    items={[
      {
        key: "help",
        label: <><span className={"fa fa-info-circle fa-fw"}></span> How to read this log</>,
        children: (
          <ul style={{ margin: 0, paddingLeft: 18, columns: 2, columnGap: 32, fontSize: 12 }}>
            <li><b>Group header</b>: changes of one request (same trace). Rows below it, with a blue left border, belong to it.</li>
            <li><b>Entity</b>: property name for <code>value</code> rows, with table and row id next to it.</li>
            <li><b>Table</b>: <span className={"fa fa-link fa-fw"}></span> marks a nested object, not the object itself.</li>
            <li><b>Operation</b>: <Tag color={"green"}>CREATE</Tag><Tag color={"geekblue"}>UPDATE</Tag><Tag color={"volcano"}>DELETE</Tag> with <code>old → new</code> for updates.</li>
            <li><b>Filters</b>: search, date, user, app, operation; <i>Direct</i> = the object itself, <i>Nested</i> = its properties and related objects.</li>
            <li><b>+</b> expands a row: request info and the changed fields (<span style={{ color: "#cf1322" }}>before</span> → <span style={{ color: "#389e0d" }}>after</span>); the <i>Show full diff</i> button opens the whole JSON comparison. The trace link lists all changes of the request.</li>
          </ul>
        ),
      },
    ]}
  />
);
