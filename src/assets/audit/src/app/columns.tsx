import React from "react";
import type { TableProps } from "antd";
import { Tooltip, Typography } from "antd";
import type { DataType, User } from "./types";
import { entityTable, formatTimestamp } from "./utils";
import { OperationTag, Restricted } from "./components/common";
import { GroupHeader } from "./components/GroupHeader";

const { Text } = Typography;

const baseColumns: NonNullable<TableProps<DataType>["columns"]> = [
  {
    title: "Timestamp",
    dataIndex: "timestamp",
    key: "timestamp",
    render: (timestamp: string, record) => {
      if (record.kind === "group") {
        return <GroupHeader group={record} />;
      }
      const url = window.location.href.split("#")[0];

      return (
        <>
          <a id={`#${record.id}`} href={`${url}#${record.id}`} target={"_blank"}>{formatTimestamp(timestamp)}</a>
          <br />
          <Text type={"secondary"}>{record.id}</Text>
        </>
      );
    },
  },
  {
    title: "User",
    dataIndex: "user",
    key: "user",
    render: (user: User) => user?.link ? <a href={user.link} target={"_blank"}>{user.login}</a> : <Restricted />,
  },
  {
    title: "Table",
    dataIndex: "table",
    key: "table",
    render: (table: string) => {
      if (!table) {
        return (
          <Restricted />
        );
      }
      if (entityTable === table || typeof entityTable === "undefined") {
        return (
          <Text>{table}</Text>
        );
      }

      return (
        <Tooltip title={"This change belongs to a nested object"}>
          <Text><span className={"fa fa-link fa-fw"}></span> {table}</Text>
        </Tooltip>
      );
    },
  },
  {
    title: "Entity",
    dataIndex: "entity",
    key: "entity",
    render: (_, { entity_id, table, diff }: DataType) => {
      if (!entity_id) {
        return <Restricted />;
      }
      // `value` rows are properties of the object: show the property name instead of the row id
      const propName = (diff.new ?? diff.old)?.prop_name;
      if (!propName) {
        return <span>{entity_id}</span>;
      }

      return (
        <>
          <Text strong>{propName}</Text>
          <br />
          <Text type={"secondary"}>{table} #{entity_id}</Text>
        </>
      );
    },
  },
  {
    title: "Operation",
    dataIndex: "operation",
    key: "operation",
    render: (_, { operation, diff }) => {
      const oldValue = diff.old?.value;
      const newValue = diff.new?.value;
      const isScalar = (v: unknown) => v !== undefined && v !== null && typeof v !== "object";

      return (
        <>
          <OperationTag operation={operation} />
          {operation === "update" && isScalar(oldValue) && isScalar(newValue) && (
            <div><Text type={"secondary"}>{String(oldValue)} → {String(newValue)}</Text></div>
          )}
        </>
      );
    },
  },
  {
    title: "Trace ID",
    dataIndex: "trace_id",
    key: "trace_id",
    render: (_, { request, inGroup }) => {
      if (inGroup) {
        return null; // already shown in the group header
      }

      return request ? <a href={request.link}>{request.trace_id}</a> : <Restricted />;
    },
  },
];
export const columns: TableProps<DataType>["columns"] = baseColumns.map((column, index, all) => ({
  ...column,
  // a group header spans the whole row
  onCell: (record: DataType) => ({ colSpan: record.kind === "group" ? (index === 0 ? all.length : 0) : 1 }),
}));
