import React from "react";
import type { TableProps } from "antd";
import { Table, Tag } from "antd";
import * as dayjs from "dayjs";
import { Differ, Viewer } from "json-diff-kit";

import "json-diff-kit/dist/viewer.css";
import "./App.css";

declare global {
  interface Window {
    __audit_data__?: string;
  }
}

interface HasLink {
  link: string;
}

interface User extends HasLink {
  id: string;
  login: string;
  impersonated_id?: number | null;
  impersonated_login?: string | null;
}

interface Request extends HasLink {
  ip: string;
  log_id: number;
  trace_id: string;
}

interface Diff {
  old: Record<string, any> | null;
  new: Record<string, any> | null;
}

interface Metadata {
  enriched: {
    captured_by: string;
    received_at: string;
  };
}

interface DataType extends HasLink {
  id: string;
  schema: string;
  table: string;
  entity_id: string;
  operation: string;
  timestamp: string;
  user: User;
  request: Request;
  diff: Diff;
  metadata: Metadata;
  key: string;
}

const colors: Record<string, string> = {
  "create": "green",
  "update": "geekblue",
  "delete": "volcano",
};

const columns: TableProps<DataType>["columns"] = [
  {
    title: "Version",
    dataIndex: "id",
    key: "id",
    render: (id: string, { link }) => <a id={`#${id}`} href={`#${id}`}>{id}</a>,
  },
  {
    title: "Timestamp",
    dataIndex: "timestamp",
    key: "timestamp",
    render: (timestamp: string) => {
      const timestampMs = parseInt(timestamp, 10);
      if (!isNaN(timestampMs)) {
        // check if timestamp in ms (13 digits)
        const timestampSec = timestampMs > 999999999999 ? Math.floor(timestampMs / 1000) : timestampMs;

        return dayjs.unix(timestampSec).format("YYYY-MM-DD HH:mm");
      }

      return timestamp;
    },
  },
  {
    title: "User",
    dataIndex: "user",
    key: "user",
    render: (user: User) => <a href={user.link} target={"_blank"}>{user.login}</a>,
  },
  {
    title: "Table",
    dataIndex: "table",
    key: "table",
  },
  {
    title: "Entity",
    dataIndex: "entity",
    key: "entity",
    render: (_, record: DataType) => <a href={record.link} target={"_blank"}>#{record.entity_id}</a>,
  },
  {
    title: "Operation",
    dataIndex: "operation",
    key: "operation",
    render: (_, { operation }) => (
      <Tag color={colors[operation] || "default"} key={operation}>
        {operation.toUpperCase()}
      </Tag>
    ),
    onFilter: (value, record) => record.operation.indexOf(value as string) === 0,
    filters: [
      {
        text: "CREATE",
        value: "create",
      },
      {
        text: "UPDATE",
        value: "update",
      },
      {
        text: "DELETE",
        value: "delete",
      },
    ],
  },
  {
    title: "Trace ID",
    dataIndex: "trace_id",
    key: "trace_id",
    render: (_, { request }) => <a href={request.link}>{request.trace_id}</a>,
  },
];


export default function App() {
  const dataSource = JSON.parse(window["__audit_data__"] || "[]") as DataType[];
  dataSource.sort((a, b) => parseInt(b.timestamp, 10) - parseInt(a.timestamp, 10));
  const version = window.location.hash.substring(1);
  let expandedRowKeys: string[] = [];
  if (version) {
    const foundItem = dataSource.find(item => item.id === version);
    expandedRowKeys = foundItem ? [foundItem.key] : [];
  }
  const differ = new Differ({
    detectCircular: true,    // default `true`
    maxDepth: Infinity,      // default `Infinity`
    showModifications: true, // default `true`
    arrayDiffMethod: "lcs",  // default `"normal"`, but `"lcs"` may be more useful
  });
  const isEmpty = (value: any): boolean => {
    if (value === null || value === undefined) return true;
    if (Array.isArray(value)) return value.length === 0;
    if (typeof value === "object") return Object.keys(value).length === 0;

    return false;
  };

  return (
    <Table<DataType>
      columns={columns}
      dataSource={dataSource}
      pagination={{ position: ["none", "none"] }}
      expandable={{
        expandedRowRender: (record) => {
          const diff = differ.diff(record.diff.old, record.diff.new);

          return (
            <p style={{ margin: 0 }}>
              <Viewer
                diff={diff}
                indent={4}                 // default `2`
                lineNumbers={true}         // default `false`
                highlightInlineDiff={true} // default `false`
                inlineDiffOptions={{
                  mode: "word",            // default `"char"`, but `"word"` may be more useful
                  wordSeparator: " ",      // default `""`, but `" "` is more useful for sentences
                }}
              />
            </p>
          );
        },
        defaultExpandedRowKeys: expandedRowKeys,
        rowExpandable: (record) => !isEmpty(record.diff.old) || !isEmpty(record.diff.new),
      }}
    />
  );
}
