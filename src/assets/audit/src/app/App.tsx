import React, { useEffect, useMemo, useState } from "react";
import type { DescriptionsProps } from "antd";
import { Alert, DatePicker, Flex, Descriptions, Input, Segmented, Select, Space, Table, TableProps, Tag, Tooltip, Typography } from "antd";
import * as dayjs from "dayjs";
import { Differ, Viewer } from "json-diff-kit";
import "json-diff-kit/dist/viewer.css";
import "./App.css";

const { Text } = Typography;

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
  ip?: string;
  log_id?: number;
  trace_id?: string;
  app?: string;
  run_id?: string;
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
  request?: Request;
  diff: Diff;
  metadata: Metadata;
  key: string;
  kind?: "group";
  rows?: DataType[];
  inGroup?: boolean;
}

const colors: Record<string, string> = {
  "create": "green",
  "update": "geekblue",
  "delete": "volcano",
};

const Restricted = () => <Text type={"danger"}>RESTRICTED</Text>;

// the timestamp can be in seconds or in ms (13 digits)
const toMs = (timestamp: string): number => {
  const value = parseInt(timestamp, 10);

  return value > 999999999999 ? value : value * 1000;
};

const formatTimestamp = (timestamp: string): string =>
  isNaN(parseInt(timestamp, 10)) ? timestamp : dayjs.unix(toMs(timestamp) / 1000).format("YYYY-MM-DD HH:mm:ss");

// the table of the object whose history is shown (undefined for the trace page)
const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;
const pageSegment = new URL(window.location.href).pathname.split("/")[2];
const entityTable = pageSegment && !uuidRegex.test(pageSegment) ? pageSegment : undefined;
const canSplitNested = entityTable !== undefined && entityTable !== "trace";

type Scope = "all" | "direct" | "nested";
interface Filters {
  search: string;
  users: string[];
  operations: string[];
  range: [number, number] | null;
  scope: Scope;
}
const emptyFilters: Filters = { search: "", users: [], operations: [], range: null, scope: "all" };

const matches = (row: DataType, { search, users, operations, range, scope }: Filters): boolean => {
  const needle = search.trim().toLowerCase();
  const data = row.diff.new ?? row.diff.old ?? {};
  const ts = toMs(row.timestamp);

  return (
    (!needle || [row.entity_id, row.table, data.prop_name, data.value].some((v) => String(v ?? "").toLowerCase().includes(needle)))
    && (users.length === 0 || users.includes(row.user?.login))
    && (operations.length === 0 || operations.includes(row.operation))
    && (!range || (ts >= range[0] && ts <= range[1]))
    && (scope === "all" || (scope === "direct") === (row.table === entityTable))
  );
};

const shortTrace = (traceId?: string): string => (traceId?.split("-")[1] ?? traceId ?? "").slice(0, 8);

const OperationTag = ({ operation, count }: { operation: string; count?: number }) => (
  <Tag color={colors[operation] || "default"}>
    {operation.toUpperCase()}{count ? ` ×${count}` : ""}
  </Tag>
);

const FilterBar = ({ filters, users, onChange }: { filters: Filters; users: string[]; onChange: (f: Filters) => void }) => {
  const set = (patch: Partial<Filters>) => onChange({ ...filters, ...patch });

  return (
    <Flex gap={"small"} wrap align={"start"} style={{ marginBottom: 16 }}>
      <Input.Search
        allowClear
        placeholder={"Property, value, entity id or table"}
        style={{ width: 300 }}
        onChange={(e) => set({ search: e.target.value })}
      />
      <DatePicker.RangePicker
        allowClear
        onChange={(dates) => set({ range: dates?.[0] && dates?.[1] ? [dates[0].startOf("day").valueOf(), dates[1].endOf("day").valueOf()] : null })}
      />
      <Select
        mode={"multiple"}
        allowClear
        placeholder={"User"}
        style={{ minWidth: 180 }}
        options={users.map((login) => ({ label: login, value: login }))}
        onChange={(users) => set({ users })}
      />
      <Select
        mode={"multiple"}
        allowClear
        placeholder={"Operation"}
        style={{ minWidth: 180 }}
        options={Object.keys(colors).map((operation) => ({ label: operation.toUpperCase(), value: operation }))}
        onChange={(operations) => set({ operations })}
      />
      {canSplitNested && (
        <Segmented<Scope>
          className={"audit-filters"}
          value={filters.scope}
          options={[{ label: "All", value: "all" }, { label: "Direct", value: "direct" }, { label: "Nested", value: "nested" }]}
          onChange={(scope) => set({ scope })}
        />
      )}
    </Flex>
  );
};

const Help = () => (
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

const GroupHeader = ({ group }: { group: DataType }) => {
  const rows = group.rows ?? [];
  const counts = rows.reduce<Record<string, number>>((acc, { operation }) => ({ ...acc, [operation]: (acc[operation] ?? 0) + 1 }), {});
  const traceId = group.request?.trace_id;

  return (
    <Space size={"middle"} wrap>
      <span className={"fa fa-code-fork fa-fw"}></span>
      <Text strong>{rows.length} changes in one request</Text>
      <Text type={"secondary"}>{formatTimestamp(group.timestamp)}</Text>
      {group.user?.link ? <a href={group.user.link} target={"_blank"}>{group.user.login}</a> : <Restricted />}
      <span>{Object.entries(counts).map(([operation, count]) => <OperationTag key={operation} operation={operation} count={count} />)}</span>
      {group.request?.link
        ? <a href={group.request.link}>{shortTrace(traceId)}</a>
        : <Restricted />}
    </Space>
  );
};

// Rows with the same trace_id (one request) are put under a synthetic header row.
const groupByTrace = (rows: DataType[]): DataType[] => {
  const byTrace = new Map<string, DataType[]>();
  rows.forEach((row) => {
    const traceId = row.request?.trace_id;
    if (traceId) {
      byTrace.set(traceId, [...(byTrace.get(traceId) ?? []), row]);
    }
  });
  const result: DataType[] = [];
  const done = new Set<string>();
  rows.forEach((row) => {
    const traceId = row.request?.trace_id;
    const members = traceId ? byTrace.get(traceId)! : [];
    if (members.length < 2) {
      result.push(row);
    } else if (!done.has(traceId!)) {
      done.add(traceId!);
      result.push({ ...members[0], key: `g:${traceId}`, kind: "group", rows: members });
      members.forEach((member) => result.push({ ...member, inGroup: true }));
    }
  });

  return result;
};


export default function App() {
  const dataSource = useMemo(() => {
    const rows = JSON.parse(window["__audit_data__"] || "[]") as DataType[];

    return rows.sort((a, b) => toMs(b.timestamp) - toMs(a.timestamp));
  }, []);
  const [filters, setFilters] = useState<Filters>(emptyFilters);
  const tableData = useMemo(() => groupByTrace(dataSource.filter((row) => matches(row, filters))), [dataSource, filters]);
  const users = useMemo(() => Array.from(new Set(dataSource.map(({ user }) => user?.login).filter(Boolean))), [dataSource]);
  const version = window.location.hash.substring(1);
  useEffect(() => {
    if (version) {
      document.getElementById(`#${version}`)?.scrollIntoView({ block: "center" });
    }
  }, []);
  let expandedRowKeys: string[] = [];
  if (version) {
    const foundItem = dataSource.find(item => item.id === version);
    expandedRowKeys = foundItem ? [foundItem.key] : [];
  }
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
  const columns: TableProps<DataType>["columns"] = baseColumns.map((column, index, all) => ({
    ...column,
    // a group header spans the whole row
    onCell: (record: DataType) => ({ colSpan: record.kind === "group" ? (index === 0 ? all.length : 0) : 1 }),
  }));

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

  const Desc: React.FC<Readonly<{ text: string }>> = (props) => (
    <Flex justify="center" align="center" style={{ height: "100%" }}>
      <Typography.Title type="secondary" level={5} style={{ whiteSpace: "nowrap" }}>
        {props.text.toUpperCase()}
      </Typography.Title>
    </Flex>
  );

  return (
    <>
    <Help />
    <FilterBar filters={filters} users={users} onChange={setFilters} />
    <Table<DataType>
      columns={columns}
      dataSource={tableData}
      rowClassName={(record) => (record.kind === "group" ? "audit-group-head" : record.inGroup ? "audit-group-child" : "")}
      pagination={{ pageSize: 100 }}
      expandable={{
        expandedRowRender: (record) => {
          const diff = differ.diff(record.diff.old, record.diff.new);
          const items: DescriptionsProps["items"] = Object.entries(record.request ?? {})
            .filter(([key]) => ["app", "log_id", "ip"].includes(key))
            .map(([key, value]) => ({
              key,
              label: key.split("_").map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(" ").toLocaleUpperCase(),
              children: value ?? "-",
            }));

          return (
            <Space direction={"vertical"} size={"large"}>
              <Descriptions size={"small"} bordered colon column={1} items={items} style={{ width: "50%" }} />
              <Flex gap={"medium"} style={{ width: "100%", height: "100%" }} justify={"space-around"} align={"center"} wrap={"wrap"}>
                <Desc text="Before" />
                <Desc text="After" />
              </Flex>
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
            </Space>
          );
        },
        defaultExpandedRowKeys: expandedRowKeys,
        rowExpandable: (record) => record.kind !== "group" && (!isEmpty(record.diff.old) || !isEmpty(record.diff.new)),
      }}
    />
    </>
  );
}
